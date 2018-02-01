<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Fixer\Operator;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * @author ntzm
 */
final class RemoveRedundantTernaryOperatorFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            $false = $tokens[$index];
            if (!$false->isGivenKind(T_STRING) || 'false' !== strtolower($false->getContent())) {
                continue;
            }

            $colonIndex = $tokens->getPrevMeaningfulToken($index);
            $colon = $tokens[$colonIndex];
            if (!$colon->equals(':')) {
                continue;
            }

            $trueIndex = $tokens->getPrevMeaningfulToken($colonIndex);
            $true = $tokens[$trueIndex];
            if (!$true->isGivenKind(T_STRING) || 'true' !== strtolower($true->getContent())) {
                continue;
            }

            $questionMarkIndex = $tokens->getPrevMeaningfulToken($trueIndex);
            $questionMark = $tokens[$questionMarkIndex];
            if (!$questionMark->equals('?')) {
                continue;
            }

            $tokens->clearRange($tokens->getPrevMeaningfulToken($questionMarkIndex) + 1, $index);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        // TODO: Implement getDefinition() method.
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound('?');
    }
}
