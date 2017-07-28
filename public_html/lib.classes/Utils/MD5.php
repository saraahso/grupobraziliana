<?php
/**
 * Classe para uso do MD5 Bidirecional
 * Desenvolvido por: Diego Rodrigo Machado
 * Alterado por: Tarcísio Xavier Gruppi <txgruppi@gmail.com>
 * 
 * Alterações:
 *      - Transformado em classe
 *      - Corrigido o erro do x13{TEXTO}
 *      - Corrigido o erro de utilização em URL
 */
class MD5 {
        /**
         * Método que gera a chave randomica para o MD5 (getenc e getdec)
         *
         * @param int $iv_len
         * @return str
         */
        function gri($iv_len)
        {
                $iv = '';
                while ($iv_len-- > 0) {
                        $iv .= chr(mt_rand() & 0xff);
                }
                return $iv;
        }

        /**
         * Método que passa uma string simples para uma string MD5
         *
         * @param str $plain_text
         * @param str $password
         * @param int $iv_len
         * @return str
         */
        function criptografar($plain_text, $password = "JONTA", $iv_len = 8) {
                if ($plain_text == "") die("Sem texto");
                if ($password == "") die("Sem senha");
                $plain_text .= "x13";
                $n = strlen($plain_text);
                if ($n % 16) $plain_text .= str_repeat("{TEXTO}", 16 - ($n % 16));
                $i = 0;
                $enc_text = $this->gri($iv_len);
                $iv = substr($password ^ $enc_text, 0, 512);
                while ($i < $n) {
                        $block = substr($plain_text, $i, 16) ^ pack('H*', MD5($iv));
                        $enc_text .= $block;
                        $iv = substr($block . $iv, 0, 512) ^ $password;
                        $i += 16;
                }

                return strtr(base64_encode($enc_text),"+/","-_");

        }

        /**
         * Método que passa uma string MD5 para uma string simples
         *
         * @param str $enc_text
         * @param str $password
         * @param int $iv_len
         * @return str
         */
        function descriptografar($enc_text, $password = "JONTA", $iv_len = 8) {
                if ($enc_text == "") die("Sem texto");
                if ($password == "") die("Sem senha");
                $enc_text = base64_decode(strtr($enc_text,"-_","+/"));
                $n = strlen($enc_text);
                $i = $iv_len;
                $plain_text = '';
                $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
                while ($i < $n) {
                        $block = substr($enc_text, $i, 16);
                        $plain_text .= $block ^ pack('H*', MD5($iv));
                        $iv = substr($block . $iv, 0, 512) ^ $password;
                        $i += 16;
                }
                $pos = strrpos($plain_text,"x13");
                return substr($plain_text,0,$pos);
        }
}
?>