<?php

declare(strict_types=1);

namespace AlanScott75\RectorLaravel\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use Rector\Core\Rector\AbstractRector;
use Rector\Defluent\NodeAnalyzer\FluentChainMethodCallNodeAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \AlanScott75\RectorLaravel\Tests\Rector\MethodCall\AppMakeToAppHelperRector\AppMakeToAppHelperRectorTest
 */

final class AppMakeToAppHelperRector extends AbstractRector
{
    public function __construct(
        private readonly FluentChainMethodCallNodeAnalyzer $fluentChainMethodCallNodeAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace `app()->make(...)` with `app(...)`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use \Illuminate\Contracts\Foundation\Application;

class MyController
{
    public function store()
    {
        return app()->make('class');
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use \Illuminate\Contracts\Foundation\Application;

class MyController
{
    public function store()
    {
        return app('class');
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'make')) {
            return null;
        }

        $rootExpr = $this->fluentChainMethodCallNodeAnalyzer->resolveRootExpr($node);
        $parentNode = $rootExpr->getAttribute(AttributeKey::PARENT_NODE);

        if (! $parentNode instanceof MethodCall) {
            return null;
        }

        if (! $parentNode->var instanceof FuncCall) {
            return null;
        }

        if ($parentNode->var->getArgs() !== []) {
            return null;
        }

        if (! $this->isName($parentNode->var->name, 'app')) {
            return null;
        }

        return new FuncCall(new Name('app'), $node->getArgs());
    }
}
