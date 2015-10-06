<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 11/09/2015
 * Time: 11:37 AM
 */

namespace Brown\SiteBundle\DQL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * "MD5" "(" StringPrimary ")"
 *
 * @category    DoctrineExtensions
 * @package     DoctrineExtensions\Query\Mysql
 * @author      Andreas Gallien <gallien@seleos.de>
 * @license     New BSD License
 */
class Md5 extends FunctionNode
{
    public $stringPrimary;

    /**
     * @override
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return $sqlWalker->getConnection()->getDatabasePlatform()->getMd5Expression(
            $sqlWalker->walkStringPrimary($this->stringPrimary)
        );
    }

    /**
     * @override
     */
    public function parse(Parser $parser)
    {
        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->stringPrimary = $parser->StringPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}