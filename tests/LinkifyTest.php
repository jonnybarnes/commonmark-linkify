<?php

use PHPUnit\Framework\TestCase;
use League\CommonMark\Converter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Jonnybarnes\CommonmarkLinkify\LinkifyExtension;

class LinkifyTest extends TestCase
{
    public $environment;
    public $converter;

    public function setUp()
    {
        $this->environment = Environment::createCommonMarkEnvironment();
        $this->environment->addExtension(new LinkifyExtension());

        $this->converter = new Converter(new DocParser($this->environment), new HtmlRenderer($this->environment));
    }

    public function test_converter()
    {
        $this->assertEquals('<h1>Hello World</h1>'.PHP_EOL, $this->converter->convertToHtml('# Hello World'));
    }

    public function test_linkify()
    {
        $input = 'A link https://example.org/bio';
        $expected = '<p>A link <a href="https://example.org/bio">example.org/bio</a></p>'.PHP_EOL;
        $this->assertEquals($expected, $this->converter->convertToHtml($input));
    }

    public function test_we_ignore_existing_anchor_tags()
    {
        $input = 'Some <a href="https://example.org">link</a> here';
        $expected = '<p>' . $input . '</p>' . PHP_EOL;
        $this->assertEquals($expected, $this->converter->convertToHtml($input));
    }

    public function test_ftp_link()
    {
        $input = 'Grab from the server ftp://some.server.com/file.tar.xz';
        $expected = '<p>Grab from the server <a href="ftp://some.server.com/file.tar.xz">some.server.com/file.tar.xz</a></p>'.PHP_EOL;
        $this->assertEquals($expected, $this->converter->convertToHtml($input));
    }
}