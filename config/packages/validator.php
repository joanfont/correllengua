<?php

return static function (Symfony\Config\FrameworkConfig $framework): void {
    $framework
        ->validation()
        ->emailValidationMode('html5');
};
