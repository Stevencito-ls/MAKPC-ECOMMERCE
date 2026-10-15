<?php
declare(strict_types=1);

use Valitron\Validator as ValitronValidator;

/**
 * ==============================================================================
 * MAKPC ENTERPRISES S.A.C. - SERVICIO DE VALIDACIÓN EMPRESARIAL (VALITRON)
 * ==============================================================================
 * Validador rápido, seguro y sin dependencias pesadas para formularios y APIs.
 */
class Validator
{
    /**
     * Instancia un validador con los datos y reglas en español.
     *
     * @param array $data Datos a validar ($_POST, JSON, etc.)
     * @param array $rules Reglas asociadas
     * @param array $labels Nombres amigables para los campos
     * @return ValitronValidator
     */
    public static function make(array $data, array $rules = [], array $labels = []): ValitronValidator
    {
        ValitronValidator::lang('es');
        $v = new ValitronValidator($data);

        if (!empty($rules)) {
            $v->rules($rules);
        }

        if (!empty($labels)) {
            $v->labels($labels);
        }

        return $v;
    }
}
