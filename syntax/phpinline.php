<?php
/**
 * DokuWiki Plugin embeddedphp (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  fiwswe <dwplugin@fwml.de>
 */
class syntax_plugin_embeddedphp_phpinline extends \dokuwiki\Extension\SyntaxPlugin
{
    /**
     * Return the tag this plugin instance reacts to
     *
     * @return string
     */
    public function GetTag(): string
    {
    	return 'php';
    }

    /** @inheritDoc */
    public function getType()
    {
        return 'formatting';
    }

    /** @inheritDoc */
    public function getPType()
    {
        return 'normal';
    }

    /** @inheritDoc */
    public function getSort()
    {
        // The default <php>/<PHP> handler up to "Igor" has priority 180. By setting a
        // lower priority we override the built-in functionality.
        return 179;
    }

    /*
     * Return the plugin Lexer mode
     *
     * @return string
     */
    public function getPluginModeName(): string
    {
        return 'plugin_'.$this->getPluginName().'_'.$this->getPluginComponent();
    }

    /** @inheritDoc */
    public function connectTo($mode)
    {
        $p = '<'.$this->GetTag().'>(?=.*?</'.$this->GetTag().'>)';
        $m = $this->getPluginModeName();
        $this->Lexer->addEntryPattern($p, $mode, $m);
    }

    /** @inheritDoc */
    public function postConnect()
    {
        $p = '</'.$this->GetTag().'>';
        $m = $this->getPluginModeName();
        $this->Lexer->addExitPattern($p, $m);
    }

    /** @inheritDoc */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        // If we are parsing a submitted comment. Executing embedded PHP in comments is
        // not a good idea!
        if (isset($_REQUEST['comment'])) {
            return false;
        }

        switch($state) {
            case DOKU_LEXER_UNMATCHED :
                // Return the data needed in $this->render() as an array:
                return [$state, $match];
        }

        return false;
    }

    /** @inheritDoc */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        if ($mode === 'xhtml') {
            if (is_array($data) && (count($data) > 1)) {
                $this->php($data[1], $renderer);

                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether embedding PHP code is allowed
     *
     * @return  bool    true if executing embedded PHP code is allowed
     */
    public function allowEmbedding(): bool
    {
        $allow = ($this->getConf('embedphpok') == 1) &&
        		 ($this->getConf('privatewiki') == 1);

        return $allow;
    }

    /**
     * Execute PHP code if allowed
     *
     * @param  string $text              PHP code that is either executed or printed
     * @param  Doku_Renderer $renderer   Renderer used for output
     * @param  string $wrapper           html element to wrap result if executing embedded PHP code is not allowed
     */
    public function php($text, Doku_Renderer $renderer, $wrapper = 'code'): void {
        if($this->allowEmbedding()) {
            ob_start();
            eval($text);
            $o = ob_get_contents();
            if (!empty($o)) {
                if ($this->isBlockElement()) {
                    $renderer->doc .= '<div class="embeddedphp">'.$o.'</div>';
                } else {
                    $renderer->doc .= '<span class="embeddedphp">'.$o.'</span>';
                }
            }
            ob_end_clean();
        } else {
            $renderer->doc .= /*'###.get_class($this)'.*/p_xhtml_cached_geshi($text, 'php', $wrapper);
        }
    }

    /**
     * Generic test to differentiate between inline and block modes
     *
     * @return bool true if this generates a block element, false otherwise.
     */
    public function isBlockElement(): bool
    {
    	return false;
    }
}

