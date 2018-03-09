<?php
/**
 * SHA3SUM Plugin
 *
 * @license    GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @author     Georg Schmidt
 */

class syntax_plugin_sha3sum extends DokuWiki_Syntax_Plugin {

    // Kind of syntax
    function getType(){
        return 'substition';
    }

    // Paragraph type
    function getPType(){
        return 'block';
    }

    // sorting order
    function getSort(){
        return -1;
    }

    // SHA3-Pattern
    function connectTo($mode) {
    $this->Lexer->addSpecialPattern('\[\[SHA3:[^]]*\]\]',$mode,'plugin_sha3sum');
    }

    // Trim Match
    function handle($match, $state, $pos, Doku_Handler $handler){
        $ret = urlencode(substr($match,7,-2)); // trim {{SHA3> from start and }} from end
        return $ret;
    }

    // Render output
    public function render($mode, Doku_Renderer $renderer, $data) {

        // $data is the result of handle
        if($mode == 'xhtml'){

            if (version_compare(phpversion(), '7.2.0', 'ge')) {
                $hash = hash('sha3-512' , $data); // SHA3 support in php 7.2
            }
            else {
                require_once(realpath(dirname(__FILE__)).'/Sha3.php'); // extenal lib under MIT license
                $hash = Sha3::hash($data, 512);
			}
           $renderer->doc .= 'SHA3-512:<br><p style="font-family: monospace; font-size: initial;">'.substr($hash,0,64).'<br>'.substr($hash,64).'</p>';

            return true;
        }
        return false;
    }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
