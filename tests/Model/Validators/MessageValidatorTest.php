<?php

declare(strict_types=1);

use App\Model\Validators\MessageValidator;
use PHPUnit\Framework\TestCase;

class MessageValidatorTest extends TestCase
{
    private const MIN_MESSAGE_LENGTH = 2;
    private const MAX_MESSAGE_LENGTH = 160;

    /**
     * @dataProvider validateMessageProvider
     * @param $input
     * @param $expected
     */
    public function testValidate($input, $expected)
    {
        $paymentRequestValidator = new MessageValidator();

        $actual = $paymentRequestValidator->validate($input);
        $this->assertEquals($expected, $actual);
    }

    public function validateMessageProvider()
    {
        return [
            [
                'input' => [],
                'expected' => [
                    'message' => 'Поле "message" не должно быть пустым'
                ],
            ],
            [
                'input' => [
                    'message' => '1',
                ],
                'expected' => [
                    'message' => 'Текст сообщения не может быть меньше ' . self::MIN_MESSAGE_LENGTH . ' символов'
                ],
            ],
            [
                'input' => [
                    'message' => '  1  ',
                ],
                'expected' => [
                    'message' => 'Текст сообщения не может быть меньше ' . self::MIN_MESSAGE_LENGTH . ' символов'
                ],
            ],
            [
                'input' => [
                    'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse felis velit, varius porta aliquet iaculis, luctus sit amet metus. Sed eget eros a nisl accumsan consequat vel at nibh. Sed tempor nulla ut malesuada dictum. Nunc condimentum massa eget ex ultricies, rutrum consequat libero lacinia. Nam ultrices efficitur justo at fringilla. Fusce ullamcorper arcu non tortor ullamcorper finibus. Proin eget felis sed odio faucibus ornare. Ut mollis mi eget auctor euismod. Aliquam a risus eu nisl facilisis tempus vitae non metus. Donec eu dui felis. Morbi ac dapibus ipsum. Proin in mauris lacus. Proin placerat mollis ligula. Quisque vestibulum posuere.',
                ],
                'expected' => [
                    'message' => 'Текст сообщения не может быть больше ' . self::MAX_MESSAGE_LENGTH . ' символов'
                ],
            ],
            [
                'input' => [
                    'message' => '    ',
                ],
                'expected' => [
                    'message' => 'Текст сообщения не может состоять только из пробелов'
                ],
            ],
            [
                'input' => [
                    'message' => 'Good message',
                ],
                'expected' => [],
            ],
        ];
    }
}
