<?php

namespace JsTokenizer;


class JTokenizerBase
{
    private $line;
    private $col;
    private $divmode;
    private $src;
    private $whitespace;
    private $unicode;
    private $regRegex;
    private $regDQuote;
    private $regSQuote;
    private $regWord;
    private $regWhite;
    private $regBreak;
    private $regJunk;
    private $regLines;
    private $regNumber;
    private $regComment;
    private $regCommentMulti;
    protected $regPunc;
    protected $Lex;

    public function __construct($whitespace, $unicode)
    {
        $this->whitespace = $whitespace;
        $this->unicode = $unicode;
        if ($this->unicode) {
            $this->regRegex = '!^/(?:\\\\.|[^\r\n\p{Zl}\p{Zp}/\\\\])+/[gi]*!u';
            $this->regDQuote = '/^"(?:\\\\(?:.|\r\n)|[^\r\n\p{Zl}\p{Zp}"\\\\])*"/su';
            $this->regSQuote = "/^'(?:\\\\(?:.|\r\n)|[^\r\n\p{Zl}\p{Zp}'\\\\])*'/su";
            $this->regWord = '/^(?:\\\\u[0-9A-F]{4,4}|[\$_\pL\p{Nl}])(?:\\\\u[0-9A-F]{4,4}|[\$_\pL\pN\p{Mn}\p{Mc}\p{Pc}])*/ui';
            $this->regWhite = '/^[\x20\x09\x0B\x0C\xA0\p{Zs}]+/u';
            $this->regBreak = '/^[\r\n\p{Zl}\p{Zp}]+/u';
            $this->regJunk = '/^./u';
            $this->regLines = '/(\r\n|[\r\n\p{Zl}\p{Zp}])/u';
        } else {
            $this->regRegex = '!^/(?:\\\\.|[^\r\n/\\\\])+/[gi]*!';
            $this->regDQuote = '/^"(?:\\\\(?:.|\r\n)|[^\r\n"\\\\])*"/s';
            $this->regSQuote = "/^'(?:\\\\(?:.|\r\n)|[^\r\n'\\\\])*'/s";
            $this->regWord = '/^[\$_A-Z][\$_A-Z0-9]*/i';
            $this->regWhite = '/^[\x20\x09\x0B\x0C\xA0]+/';
            $this->regBreak = '/^[\r\n]+/';
            $this->regJunk = '/^./';
            $this->regLines = '/(\r\n|\r|\n)/';
        }
        $this->regNumber = '/^(?:0x[A-F0-9]+|\d*\.\d+(?:E(?:\+|\-)?\d+)?|\d+)/i';
        $this->regComment = '/^\/\/.*/';
        $this->regCommentMulti = '/^\/\*.*\*\//Us';
    }

    public function init($src)
    {
        $this->src = $src;
        $this->line = 1;
        $this->col = 1;
        $this->divmode = false;
    }

    public function getAllTokens($src)
    {
        $this->init($src);
        $tokens = [];
        while ($this->src) {
            $token = $this->getNextToken() and $tokens[] = $token;
        }

        return $tokens;
    }

    public function getNextToken()
    {
        $c = $this->src{0};
        if ($c === '"') {
            if (!preg_match($this->regDQuote, $this->src, $r)) {
                trigger_error("Unterminated string constant on line $this->line", E_USER_NOTICE);
                $s = $t = '"';
            } else {
                $s = $r[0];
                $t = Lex::J_STRING_LITERAL;
            }
            $this->divmode = true;
        } else {
            if ($c === "'") {
                if (!preg_match($this->regSQuote, $this->src, $r)) {
                    trigger_error("Unterminated string constant on line $this->line", E_USER_NOTICE);
                    $s = $t = "'";
                } else {
                    $s = $r[0];
                    $t = Lex::J_STRING_LITERAL;
                }
                $this->divmode = true;
            } else {
                if ($c === '/') {
                    if ($this->src{1} === '/' && preg_match($this->regComment, $this->src, $r)) {
                        $t = $this->whitespace ? Lex::J_COMMENT : false;
                        $s = $r[0];
                    } else {
                        if ($this->src{1} === '*' && preg_match($this->regCommentMulti, $this->src, $r)) {
                            $s = $r[0];
                            if ($this->whitespace) {
                                $t = Lex::J_COMMENT;
                            } else {
                                $breaks = preg_match($this->regLines, $s, $r);
                                $t = $breaks ? Lex::J_LINE_TERMINATOR : false;
                            }
                        } else {
                            if (!$this->divmode) {
                                if (!preg_match($this->regRegex, $this->src, $r)) {
                                    trigger_error("Bad regular expression literal on line $this->line", E_USER_NOTICE);
                                    $s = $t = '/';
                                    $this->divmode = false;
                                } else {
                                    $s = $r[0];
                                    $t = Lex::J_REGEX;
                                    $this->divmode = true;
                                }
                            } else {
                                if ($this->src{1} === '=') {
                                    $s = $t = '/=';
                                    $this->divmode = false;
                                } else {
                                    $s = $t = '/';
                                    $this->divmode = false;
                                }
                            }
                        }
                    }
                } else {
                    if (preg_match($this->regBreak, $this->src, $r)) {
                        $t = Lex::J_LINE_TERMINATOR;
                        $s = $r[0];
                        $this->divmode = false;
                    } else {
                        if (preg_match($this->regWhite, $this->src, $r)) {
                            $t = $this->whitespace ? Lex::J_WHITESPACE : false;
                            $s = $r[0];
                        } else {
                            if (preg_match($this->regNumber, $this->src, $r)) {
                                $t = Lex::J_NUMERIC_LITERAL;
                                $s = $r[0];
                                $this->divmode = true;
                            } else {
                                if (preg_match($this->regWord, $this->src, $r)) {
                                    $s = $r[0];
                                    $t = $this->Lex->is_word($s) or $t = Lex::J_IDENTIFIER;
                                    switch ($t) {
                                        case Lex::J_IDENTIFIER;
                                            $this->divmode = true;
                                            break;
                                        default:
                                            $this->divmode = null;
                                    }
                                } else {
                                    if (preg_match($this->regPunc, $this->src, $r)) {
                                        $s = $t = $r[0];
                                        switch ($t) {
                                            case ']':
                                            case ')':
                                                $this->divmode = true;
                                                break;
                                            default:
                                                $this->divmode = false;
                                        }
                                    } else {
                                        preg_match($this->regJunk, $this->src, $r);
                                        $s = $t = $r[0];
                                        trigger_error("Junk on line $this->line, $s", E_USER_NOTICE);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $len = strlen($s);
        if ($len === 0) {
            throw new \Exception('Failed to extract anything');
        }
        if ($t !== false) {
            $token = [$t, $s, $this->line, $this->col];
        }
        $this->src = substr($this->src, $len);
        if ($t === Lex::J_LINE_TERMINATOR || $t === Lex::J_COMMENT) {
            $this->line += preg_match_all($this->regLines, $s, $r);
            $cbreak = end($r[0]);
            $this->col = $len - strrpos($s, $cbreak);
        } else {
            $this->col += $len;
        }

        return isset($token) ? $token : null;
    }
}
