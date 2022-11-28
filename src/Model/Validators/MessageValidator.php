<?php

namespace App\Model\Validators;

class MessageValidator implements ValidatorInterface
{
    private const NOT_EMPTY_FIELDS = ['message'];
    private const MIN_MESSAGE_LENGTH = 2;
    private const MAX_MESSAGE_LENGTH = 160;

    public function validate(array $data): array
    {
        $errors = $this->validateNotEmpty($data);

        if (!empty($errors)) {
            return $errors;
        }

        return array_merge(
            $this->validateLength($data),
            $this->validateSpaces($data),
        );
    }

    private function validateNotEmpty(array $data): array
    {
        $errors = [];

        foreach (self::NOT_EMPTY_FIELDS as $fieldName) {
            $value = $data[$fieldName] ?? null;

            if (empty($value)) {
                $errors[$fieldName] = 'Поле "' . $fieldName . '" не должно быть пустым';
            }
        }

        return $errors;
    }

    private function validateLength(array $data): array
    {
        $messageLength = mb_strlen(trim($data['message']));

        if ($messageLength < self::MIN_MESSAGE_LENGTH) {
            return [
                'message' => 'Текст сообщения не может быть меньше ' . self::MIN_MESSAGE_LENGTH . ' символов',
            ];
        }

        if ($messageLength > self::MAX_MESSAGE_LENGTH) {
            return [
                'message' => 'Текст сообщения не может быть больше ' . self::MAX_MESSAGE_LENGTH . ' символов',
            ];
        }

        return [];
    }

    private function validateSpaces(array $data): array
    {
        $message = $data['message'];

        if (trim($message) == '')
        {
            return [
                'message' => 'Текст сообщения не может состоять только из пробелов',
            ];
        }

        return [];
    }
}
