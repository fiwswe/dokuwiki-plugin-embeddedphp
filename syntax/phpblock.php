<?php
/**
 * DokuWiki Plugin embeddedphp (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author	fiwswe <dwplugin@fwml.de>
 */

class syntax_plugin_embeddedphp_phpblock extends syntax_plugin_embeddedphp_phpinline
{
	/** @inheritDoc */
	protected function GetTag(): string
	{
		return 'PHP';
	}

	/** @inheritDoc */
	protected function isBlockElement(): bool
	{
		return true;
	}
}

