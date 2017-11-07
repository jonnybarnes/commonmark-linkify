<?php

namespace Jonnybarnes\CommonmarkLinkify;

use League\CommonMark\InlineParserContext;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Parser\AbstractInlineParser;

class LinkifyParser extends AbstractInlineParser
{
    public function getCharacters()
    {
        return ['f', 'h'];
    }

    public function parse(InlineParserContext $inlineContext)
    {
        $cursor = $inlineContext->getCursor();
        $startChar = $cursor->peek(0);
        // the h/f symbol must be at the start or preceded by a space
        $previousChar = $cursor->peek(-1);
        if ($previousChar !== null && $previousChar !== ' ') {
            // peek() doesn't modify the cursor, so no need to restore state first
            return false;
        }
        // Save the cursor state in case we need to rewind and bail
        $previousState = $cursor->saveState();
        // Parse for a URL
        $cursor->advance();
        $match = $cursor->match('/^[t]?tp[s]?:\/\/([^\s]*)/i');
        if (empty($match)) {
            // regex failed to match, this isn’t a valid url
            $cursor->restoreState($previousState);

            return false;
        }
        $link = $startChar . $match;
        $anchor = ltrim(strstr($match, '//'), '/');
        $inlineContext->getContainer()->appendChild(new Link($link, $anchor));

        return true;
    }
}