<?php

namespace JsTokenizer;


class Lex
{
    const P_EPSILON = -1;
    const P_EOF     = -2;
    const P_GOAL    = -3;

    const J_FUNCTION            = 1;
    const J_IDENTIFIER          = 2;
    const J_VAR                 = 3;
    const J_IF                  = 4;
    const J_ELSE                = 5;
    const J_DO                  = 6;
    const J_WHILE               = 7;
    const J_FOR                 = 8;
    const J_IN                  = 9;
    const J_CONTINUE            = 10;
    const J_BREAK               = 11;
    const J_RETURN              = 12;
    const J_WITH                = 13;
    const J_SWITCH              = 14;
    const J_CASE                = 15;
    const J_DEFAULT             = 16;
    const J_THROW               = 17;
    const J_TRY                 = 18;
    const J_CATCH               = 19;
    const J_FINALLY             = 20;
    const J_THIS                = 21;
    const J_STRING_LITERAL      = 22;
    const J_NUMERIC_LITERAL     = 23;
    const J_TRUE                = 24;
    const J_FALSE               = 25;
    const J_NULL                = 26;
    const J_REGEX               = 27;
    const J_NEW                 = 28;
    const J_DELETE              = 29;
    const J_VOID                = 30;
    const J_TYPEOF              = 31;
    const J_INSTANCEOF          = 32;
    const J_COMMENT             = 33;
    const J_WHITESPACE          = 34;
    const J_LINE_TERMINATOR     = 35;
    const J_ABSTRACT            = 36;
    const J_ENUM                = 37;
    const J_INT                 = 38;
    const J_SHORT               = 39;
    const J_BOOLEAN             = 40;
    const J_EXPORT              = 41;
    const J_INTERFACE           = 42;
    const J_STATIC              = 43;
    const J_BYTE                = 44;
    const J_EXTENDS             = 45;
    const J_LONG                = 46;
    const J_SUPER               = 47;
    const J_CHAR                = 48;
    const J_FINAL               = 49;
    const J_NATIVE              = 50;
    const J_SYNCHRONIZED        = 51;
    const J_CLASS               = 52;
    const J_FLOAT               = 53;
    const J_PACKAGE             = 54;
    const J_THROWS              = 55;
    const J_CONST               = 56;
    const J_GOTO                = 57;
    const J_PRIVATE             = 58;
    const J_TRANSIENT           = 59;
    const J_DEBUGGER            = 60;
    const J_IMPLEMENTS          = 61;
    const J_PROTECTED           = 62;
    const J_VOLATILE            = 63;
    const J_DOUBLE              = 64;
    const J_IMPORT              = 65;
    const J_PUBLIC              = 66;
    const J_PROGRAM             = 67;
    const J_ELEMENTS            = 68;
    const J_ELEMENT             = 69;
    const J_STATEMENT           = 70;
    const J_FUNC_DECL           = 71;
    const J_PARAM_LIST          = 72;
    const J_FUNC_BODY           = 73;
    const J_FUNC_EXPR           = 74;
    const J_BLOCK               = 75;
    const J_VAR_STATEMENT       = 76;
    const J_EMPTY_STATEMENT     = 77;
    const J_EXPR_STATEMENT      = 78;
    const J_IF_STATEMENT        = 79;
    const J_ITER_STATEMENT      = 80;
    const J_CONT_STATEMENT      = 81;
    const J_BREAK_STATEMENT     = 82;
    const J_RETURN_STATEMENT    = 83;
    const J_WITH_STATEMENT      = 84;
    const J_LABELLED_STATEMENT  = 85;
    const J_SWITCH_STATEMENT    = 86;
    const J_THROW_STATEMENT     = 87;
    const J_TRY_STATEMENT       = 88;
    const J_STATEMENT_LIST      = 89;
    const J_VAR_DECL_LIST       = 90;
    const J_VAR_DECL            = 91;
    const J_VAR_DECL_LIST_NO_IN = 92;
    const J_VAR_DECL_NO_IN      = 93;
    const J_INITIALIZER         = 94;
    const J_INITIALIZER_NO_IN   = 95;
    const J_ASSIGN_EXPR         = 96;
    const J_ASSIGN_EXPR_NO_IN   = 97;
    const J_EXPR                = 98;
    const J_EXPR_NO_IN          = 99;
    const J_LHS_EXPR            = 100;
    const J_CASE_BLOCK          = 101;
    const J_CASE_CLAUSES        = 102;
    const J_CASE_DEFAULT        = 103;
    const J_CASE_CLAUSE         = 104;
    const J_CATCH_CLAUSE        = 105;
    const J_FINALLY_CLAUSE      = 106;
    const J_PRIMARY_EXPR        = 107;
    const J_ARRAY_LITERAL       = 108;
    const J_OBJECT_LITERAL      = 109;
    const J_ELISION             = 110;
    const J_ELEMENT_LIST        = 111;
    const J_PROP_LIST           = 112;
    const J_PROP_NAME           = 113;
    const J_MEMBER_EXPR         = 114;
    const J_ARGS                = 115;
    const J_NEW_EXPR            = 116;
    const J_CALL_EXPR           = 117;
    const J_ARG_LIST            = 118;
    const J_POSTFIX_EXPR        = 119;
    const J_UNARY_EXPR          = 120;
    const J_MULT_EXPR           = 121;
    const J_ADD_EXPR            = 122;
    const J_SHIFT_EXPR          = 123;
    const J_REL_EXPR            = 124;
    const J_REL_EXPR_NO_IN      = 125;
    const J_EQ_EXPR             = 126;
    const J_EQ_EXPR_NO_IN       = 127;
    const J_BIT_AND_EXPR        = 128;
    const J_BIT_AND_EXPR_NO_IN  = 129;
    const J_BIT_XOR_EXPR        = 130;
    const J_BIT_XOR_EXPR_NO_IN  = 131;
    const J_BIT_OR_EXPR         = 132;
    const J_BIT_OR_EXPR_NO_IN   = 133;
    const J_LOG_AND_EXPR        = 134;
    const J_LOG_AND_EXPR_NO_IN  = 135;
    const J_LOG_OR_EXPR         = 136;
    const J_LOG_OR_EXPR_NO_IN   = 137;
    const J_COND_EXPR           = 138;
    const J_COND_EXPR_NO_IN     = 139;
    const J_ASSIGN_OP           = 140;
    const J_IGNORE              = 141;
    const J_RESERVED            = 142;

    private static $singletons = [];
    protected $i;
    protected $names = [
        self::P_EPSILON => 'P_EPSILON',
        self::P_EOF     => 'P_EOF',
        self::P_GOAL    => 'P_GOAL',
    ];
    protected $literals = [];

    public function __construct($i = null)
    {
        if (!is_null($i)) {
            $this->i = (int)$i;
        }
    }

    public function destroy()
    {
        $class = get_class($this);
        unset(self::$singletons[$class]);
        unset($this->names);
        unset($this->literals);
    }

    public static function getInstance($class)
    {
        if (!isset(self::$singletons[$class])) {
            self::$singletons[$class] = new $class();
        }

        return self::$singletons[$class];
    }

    public function defined($c)
    {
        if (isset($this->literals[$c])) {
            return true;
        }
        if (!defined($c)) {
            return false;
        }
        $i = constant($c);

        return isset($this->names[$i]) && $this->names[$i] === $c;
    }

    public function name($i)
    {
        if (is_int($i)) {
            if (!isset($this->names[$i])) {
                trigger_error("symbol ".var_export($i, 1)." is unknown in ".get_class($this), E_USER_NOTICE);

                return 'UNKNOWN';
            } else {
                return $this->names[$i];
            }
        } else {
            if (!isset($this->literals[$i])) {
                trigger_error("literal symbol ".var_export($i, 1)." is unknown in ".get_class($this),
                    E_USER_NOTICE);
            }
        }

        return $i;
    }

    public function implode($s, array $a)
    {
        $b = [];
        foreach ($a as $t) {
            $b[] = $this->name($t);
        }

        return implode($s, $b);
    }

    public function dump()
    {
        asort($this->names, SORT_STRING);
        $t = max(2, strlen((string)$this->i));
        foreach ($this->names as $i => $n) {
            $i = str_pad($i, $t, ' ', STR_PAD_LEFT);
            echo "$i => $n \n";
        }
    }
}
