<?php
/**
 * DokuWiki Plugin embeddedphp (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  fiwswe <dwplugin@fwml.de>
 */
class syntax_plugin_embeddedphp_phpblock extends syntax_plugin_embeddedphp_phpinline
{
    /** @inheritDoc */
    public function GetTag(): string
    {
    	return 'PHP';
    }

    /** @inheritDoc */
    public function getPType()
    {
        return 'block';
    }

    /** @inheritDoc */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        if ($mode === 'xhtml') {
            if (is_array($data) && (count($data) > 1)) {
                $this->phpblock($data[1], $renderer);

                return true;
            }
        }

        return false;
    }

    /**
     * Output block level PHP code
     *
     * If $conf['phpok'] is true this should evaluate the given code and append the result
     * to $doc
     *
     * @param string $text The PHP code
     * @param  Doku_Renderer $renderer   Renderer used for output
     */
    public function phpblock($text, Doku_Renderer $renderer): void {
        $this->php($text, $renderer, 'pre');
    }

    /** @inheritDoc */
    public function isBlockElement(): bool
    {
    	return true;
    }
}

