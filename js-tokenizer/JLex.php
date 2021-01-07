<?php

namespace JsTokenizer;


class JLex extends JLexBase
{
    protected $words = [
        'true'         => Lex::J_TRUE,
        'false'        => Lex::J_FALSE,
        'null'         => Lex::J_NULL,
        'break'        => Lex::J_BREAK,
        'else'         => Lex::J_ELSE,
        'new'          => Lex::J_NEW,
        'var'          => Lex::J_VAR,
        'case'         => Lex::J_CASE,
        'finally'      => Lex::J_FINALLY,
        'return'       => Lex::J_RETURN,
        'void'         => Lex::J_VOID,
        'catch'        => Lex::J_CATCH,
        'for'          => Lex::J_FOR,
        'switch'       => Lex::J_SWITCH,
        'while'        => Lex::J_WHILE,
        'continue'     => Lex::J_CONTINUE,
        'function'     => Lex::J_FUNCTION,
        'this'         => Lex::J_THIS,
        'with'         => Lex::J_WITH,
        'default'      => Lex::J_DEFAULT,
        'if'           => Lex::J_IF,
        'throw'        => Lex::J_THROW,
        'delete'       => Lex::J_DELETE,
        'in'           => Lex::J_IN,
        'try'          => Lex::J_TRY,
        'do'           => Lex::J_DO,
        'instanceof'   => Lex::J_INSTANCEOF,
        'typeof'       => Lex::J_TYPEOF,
        'abstract'     => Lex::J_ABSTRACT,
        'enum'         => Lex::J_ENUM,
        'int'          => Lex::J_INT,
        'short'        => Lex::J_SHORT,
        'boolean'      => Lex::J_BOOLEAN,
        'export'       => Lex::J_EXPORT,
        'interface'    => Lex::J_INTERFACE,
        'static'       => Lex::J_STATIC,
        'byte'         => Lex::J_BYTE,
        'extends'      => Lex::J_EXTENDS,
        'long'         => Lex::J_LONG,
        'super'        => Lex::J_SUPER,
        'char'         => Lex::J_CHAR,
        'final'        => Lex::J_FINAL,
        'native'       => Lex::J_NATIVE,
        'synchronized' => Lex::J_SYNCHRONIZED,
        'class'        => Lex::J_CLASS,
        'float'        => Lex::J_FLOAT,
        'package'      => Lex::J_PACKAGE,
        'throws'       => Lex::J_THROWS,
        'const'        => Lex::J_CONST,
        'goto'         => Lex::J_GOTO,
        'private'      => Lex::J_PRIVATE,
        'transient'    => Lex::J_TRANSIENT,
        'debugger'     => Lex::J_DEBUGGER,
        'implements'   => Lex::J_IMPLEMENTS,
        'protected'    => Lex::J_PROTECTED,
        'volatile'     => Lex::J_VOLATILE,
        'double'       => Lex::J_DOUBLE,
        'import'       => Lex::J_IMPORT,
        'public'       => Lex::J_PUBLIC,
    ];

    public function isWord($s)
    {
        return isset($this->words[$s]) ? $this->words[$s] : false;
    }

    public static function singleton()
    {
        return Lex::getInstance(__CLASS__);
    }
}
