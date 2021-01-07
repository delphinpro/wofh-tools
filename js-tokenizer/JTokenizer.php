<?php

namespace JsTokenizer;


class JTokenizer extends JTokenizerBase
{
    protected $regPunc = '/(?:\>\>\>\=|\>\>\>|\<\<\=|\>\>\=|\!\=\=|\=\=\=|&&|\<\<|\>\>|\|\||\*\=|\|\=|\^\=|&\=|%\=|-\=|\+\+|\+\=|--|\=\=|\>\=|\!\=|\<\=|;|,|\<|\>|\.|\]|\}|\(|\)|\[|\=|\:|\||&|-|\{|\^|\!|\?|\*|%|~|\+)/';

    public function __construct($whitespace = true, $unicode = true)
    {
        parent::__construct($whitespace, $unicode);
        $this->Lex = Lex::getInstance('JLex');
    }
}
