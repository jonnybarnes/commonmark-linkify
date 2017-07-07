<?php

namespace Jonnybarnes\CommonmarkLinkify;

use League\CommonMark\Extension\Extension;

class LinkifyExtension extends Extension
{
    public function getInlineParsers()
    {
        return [new LinkifyParser()];
    }

    public function getName()
    {
        return 'linkify';
    }
}