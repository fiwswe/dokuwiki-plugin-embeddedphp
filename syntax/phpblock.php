<?php
/**
 * DokuWiki Plugin embeddedphp (Syntax Component)
 *
 * @license MIT https://mit-license.org
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

