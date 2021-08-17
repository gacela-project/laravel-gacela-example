<?php

use Gacela\Framework\AbstractConfigGacela;

return static function (array $globalServices): AbstractConfigGacela {
    return new class($globalServices) extends AbstractConfigGacela {
        public function config(): array
        {
            return [
                [
                    'type' => 'env',
                    'path' => '.env*',
                    'path_local' => '.env',
                ],
                [
                    'type' => 'php',
                    'path' => 'config/*.php',
                ],
            ];
        }
    };
};
