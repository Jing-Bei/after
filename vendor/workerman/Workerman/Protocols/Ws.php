<?php
namespace Workerman\Protocols;

use Workerman\Worker;

/**
 * Websocket protocol for client.
 */
class Ws
{
    /**
     * Minimum head length of websocket protocol.
     *
     * @var int
     */
    const MIN_HEAD_LEN = 2;

    /**
     * Websocket blob type.
     *
     * @var string
     */
    const BINARY_TYPE_BLOB = "\x81";

    /**
     * Websocket arraybuffer type.
     *
     * @var string
     */
    const BINARY_TYPE_ARRAYBUFFER = "\x82";

    /**
     * Check the integrity of the package.
     *
     * @param string              $buffer
     * @param ConnectionInterface $connection
     * @return int
     */
    public static function input($buffer, $connection)
    {
        if (empty($connection->handshakeStep)) {
            echo "recv data before handshake\n";
            return false;
        }
        // Recv handshake response
        if ($connection->handshakeStep === 1) {
            return self::dealHandshake($buffer, $connection);
        }
        $recv_len = strlen($buffer);
        if ($recv_len < self::MIN_HEAD_LEN) {
            return 0;
        }
        // Buffer websocket frame data.
        if ($connection->websocketCurrentFrameLength) {
            // We need more frame data.
            if ($connection->websocketCurrentFrameLength > $recv_len) {
                // Return 0, because it is not clear the full packet length, waiting for the frame of fin=1.
                return 0;
            }
        } else {
            $data_len     = ord($buffer[1]) & 127;
            $firstbyte    = ord($buffer[0]);
            $is_fin_frame = $firstbyte >> 7;
            $opcode       = $firstbyte & 0xf;
            switch ($opcode) {
                case 0x0:
                    break;
                // Blob type.
                case 0x1:
                    break;
                // Arraybuffer type.
                case 0x2:
                    break;
                // Close package.
                case 0x8:
                    // Try to emit onWebSocketClose callback.
                    if (isset($connection->onWebSocketClose)) {
                        try {
                            call_user_func($connection->onWebSocketClose, $connection);
                        } catch (\Exception $e) {
                            Worker::log($e);
                            exit(250);
                        } catch (\Error $e) {
                            Worker::log($e);
                            exit(250);
                        }
                    } // Close connection.
                    else {
                        $connection->close();
                    }
                    return 0;
                // Ping package.
                case 0x9:
                    // Try to emit onWebSocketPing callback.
                    if (isset($connection->onWebSocketPing)) {
                        try {
                            call_user_func($connection->onWebSocketPing, $connection);
                        } catch (\Exception $e) {
                            Worker::log($e);
                            exit(250);
                        } catch (\Error $e) {
                            Worker::log($e);
                            exit(250);
                        }
                    } // Send pong package to client.
                    else {
                        $connection->send(pack('H*', '8a00'), true);
                    }
                    // Consume data from receive buffer.
                    if (!$data_len) {
                        $connection->consumeRecvBuffer(self::MIN_HEAD_LEN);
                        if ($recv_len > self::MIN_HEAD_LEN) {
                            return self::input(substr($buffer, self::MIN_HEAD_LEN), $connection);
                        }
                        return 0;
                    }
                    break;
                // Pong package.
                case 0xa:
                    // Try to emit onWebSocketPong callback.
                    if (isset($connection->onWebSocketPong)) {
                        try {
                            call_user_func($connection->onWebSocketPong, $connection);
                        } catch (\Exception $e) {
                            Worker::log($e);
                            exit(250);
                        } catch (\Error $e) {
                            Worker::log($e);
                            exit(250);
                        }
                    }
                    //  Consume data from receive buffer.
                    if (!$data_len) {
                        $connection->consumeRecvBuffer(self::MIN_HEAD_LEN);
                        if ($recv_len > self::MIN_HEAD_LEN) {
                            return self::input(substr($buffer, self::MIN_HEAD_LEN), $connection);
                        }
                        return 0;
                    }
                    break;
                // Wrong opcode. 
                default :
                    echo "error opcode $opcode and close websocket connection\n";
                    $connection->close();
                    return 0;
            }
            // Calculate packet length.
            if ($data_len === 126) {
                if (strlen($buffer) < 6) {
                    return 0;
                }
                $pack = unpack('nn/ntotal_len', $buffer);
                $current_frame_length = $pack['total_len'] + 4;
            } else if ($data_len === 127) {
                if (strlen($buffer) < 10) {
                    return 0;
                }
                $arr = unpack('n/N2c', $buffer);
                $current_frame_length = $arr['c1']*4294967296 + $arr['c2'] + 10;
            } else {
                $current_frame_length = $data_len + 2;
            }
            if ($is_fin_frame) {
                return $current_frame_length;
            } else {
                $connection->websocketCurrentFrameLength = $current_frame_length;
            }
        }
        // Received just a frame length data.
        if ($connection->websocketCurrentFrameLength === $recv_len) {
            self::decode($buffer, $connection);
            $connection->consumeRecvBuffer($connection->websocketCurrentFrameLength);
            $connection->websocketCurrentFrameLength = 0;
            return 0;
        } // The length of the received data is greater than the length of a frame.
        elseif ($connection->websocketCurrentFrameLength < $recv_len) {
            self::decode(substr($buffer, 0, $connection->websocketCurrentFrameLength), $connection);
            $connection->consumeRecvBuffer($connection->websocketCurrentFrameLength);
            $current_frame_length                    = $connection->websocketCurrentFrameLength;
            $connection->websocketCurrentFrameLength = 0;
            // Continue to read next frame.
            return self::input(substr($buffer, $current_frame_length), $connection);
        } // The length of the received data is less than the length of a frame.
        else {
            return 0;
        }
    }

    /**
     * Websocket encode.
     *
     * @param string              $buffer
     * @param ConnectionInterface $connection
     * @return string
     */
    public static function encode($payload, $connection)
    {
        $payload = (string)$payload;
        if (empty($connection->handshakeStep)) {
            self::sendHandshake($connection);
        }
        $mask = 1;
        $mask_key = "\x00\x00\x00\x00";

        $pack = '';
        $length = $length_flag = strlen($payload);
        if (65535 < $length) {
            $pack   = pack('NN', ($length & 0xFFFFFFFF00000000) >> 32, $length & 0x00000000FFFFFFFF);
            $length_flag = 127;
        } else if (125 < $length) {
            $pack   = pack('n*', $length);
            $length_flag = 126;
        }

        $head = ($mask << 7) | $length_flag;
        $head = $connection->websocketType . chr($head) . $pack;

        $frame = $head . $mask_key;
        // append payload to frame:
        for ($i = 0; $i < $length; $i++) {
            $frame .= $payload[$i] ^ $mask_key[$i % 4];
        }
        if ($connection->handshakeStep === 1) {
            $connection->tmpWebsocketData = isset($connection->tmpWebsocketData) ? $connection->tmpWebsocketData . $frame : $frame;
            return '';
        }
        return $frame;
    }

    /**
     * Websocket decode.
     *
     * @param string              $buffer
     * @param ConnectionInterface $connection
     * @return string
     */
    public static function decode($bytes, $connection)
    {
        $masked = $bytes[1] >> 7;
        $data_length = $masked ? ord($bytes[1]) & 127 : ord($bytes[1]);
        $decoded_data = '';
        if ($masked === true) {
            if ($data_length === 126) {
                $mask = substr($bytes, 4, 4);
                $coded_data = substr($bytes, 8);
            } else if ($data_length === 127) {
                $mask = substr($bytes, 10, 4);
                $coded_data = substr($bytes, 14);
            } else {
                $mask = substr($bytes, 2, 4);
                $coded_data = substr($bytes, 6);
            }
            for ($i = 0; $i < strlen($coded_data); $i++) {
                $decoded_data .= $coded_data[$i] ^ $mask[$i % 4];
            }
        } else {
            if ($data_length === 126) {
                $decoded_data = substr($bytes, 4);
            } else if ($data_length === 127) {
                $decoded_data = substr($bytes, 10);
            } else {
                $decoded_data = substr($bytes, 2);
            }
        }
        if ($connection->websocketCurrentFrameLength) {
            $connection->websocketDataBuffer .= $decoded_data;
            return $connection->websocketDataBuffer;
        } else {
            if ($connection->websocketDataBuffer !== '') {
                $decoded_data                    = $connection->websocketDataBuffer . $decoded_data;
                $connection->websocketDataBuffer = '';
            }
            return $decoded_data;
        }
    }

    /**
     * Send websocket handshake.
     *
     * @param \Workerman\Connection\TcpConnection $connection
     * @return void 
     */
    public static function sendHandshake($connection)
    {
        // Get Host.
        $port = $connection->getRemotePort();
        $host = $port === 80 ? $connection->getRemoteHost() : $connection->getRemoteHost() . ':' . $port;
        // Handshake header.
        $header = "GET / HTTP/1.1\r\n".
        "Host: $host\r\n".
        "Connection: Upgrade\r\n".
        "Upgrade: websocket\r\n".
        "Origin: ". (isset($connection->websocketOrigin) ? $connection->websocketOrigin : '*') ."\r\n".
        "Sec-WebSocket-Version: 13\r\n".
        "Sec-WebSocket-Key: ".base64_encode(sha1(uniqid(mt_rand(), true), true))."\r\n\r\n";
        $connection->send($header, true);
        $connection->handshakeStep               = 1;
        $connection->websocketCurrentFrameLength = 0;
        $connection->websocketDataBuffer  = '';
        if (empty($connection->websocketType)) {
            $connection->websocketType = self::BINARY_TYPE_BLOB;
        }
    }

    /**
     * Websocket handshake.
     *
     * @param string                              $buffer
     * @param \Workerman\Connection\TcpConnection $connection
     * @return int
     */
    public static function dealHandshake($buffer, $connection)
    {
        $pos = strpos($buffer, "\r\n\r\n");
        if ($pos) {
            // handshake complete
            $connection->handshakeStep = 2;
            $handshake_respnse_length = $pos + 4;
            // Try to emit onWebSocketConnect callback.
            if (isset($connection->onWebSocketConnect)) {
                try {
                    call_user_func($connection->onWebSocketConnect, $connection, substr($buffer, 0, $handshake_respnse_length));
                } catch (\Exception $e) {
                    Worker::log($e);
                    exit(250);
                } catch (\Error $e) {
                    Worker::log($e);
                    exit(250);
                }
            }
            // Headbeat.
            if (!empty($connection->websocketPingInterval)) {
                $connection->websocketPingTimer = \Workerman\Lib\Timer::add($connection->websocketPingInterval, function() use ($connection){
                    if (false === $connection->send(pack('H*', '8900'), true)) {
                        \Workerman\Lib\Timer::del($connection->websocketPingTimer);
                    }
                });
            }

            $connection->consumeRecvBuffer($handshake_respnse_length);
            if (!empty($connection->tmpWebsocketData)) {
                $connection->send($connection->tmpWebsocketData, true);
            }
            if (strlen($buffer > $handshake_respnse_length)) {
                return self::input(substr($buffer, $handshake_respnse_length));
            }
        }
        return 0;
    }
}
