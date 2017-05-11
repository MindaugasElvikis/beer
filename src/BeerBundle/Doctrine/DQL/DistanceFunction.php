<?php

namespace BeerBundle\Doctrine\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\InputParameter;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class DistanceFunction.
 */
class DistanceFunction extends FunctionNode
{
    /**
     * @var InputParameter
     */
    public $remoteLat;

    /**
     * @var InputParameter
     */
    public $remoteLong;

    /**
     * @var InputParameter
     */
    public $currentLat;

    /**
     * @var InputParameter
     */
    public $currentLong;

    /**
     * @param Parser $parser
     *
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->remoteLat = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);

        $this->remoteLong = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);

        $this->currentLat = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);

        $this->currentLong = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param SqlWalker $sqlWalker
     *
     * @return string
     *
     * @throws \Doctrine\ORM\Query\AST\ASTException
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            '(6371 * ACOS(SIN(RADIANS(%s)) * SIN(RADIANS(%s)) + COS(RADIANS(%s)) * COS(RADIANS(%s)) * COS(RADIANS(%s) - RADIANS(%s))))',
            $this->currentLat->dispatch($sqlWalker),
            $this->remoteLat->dispatch($sqlWalker),
            $this->currentLat->dispatch($sqlWalker),
            $this->remoteLat->dispatch($sqlWalker),
            $this->remoteLong->dispatch($sqlWalker),
            $this->currentLong->dispatch($sqlWalker)
        );
    }
}
