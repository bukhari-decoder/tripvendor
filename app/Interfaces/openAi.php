<?php

namespace App\Interfaces;

interface openAi
{
    const OPENAI_BASE_URL = "https://api.openai.com/v1/";
    const OPENAI_CHAT_URL = self::OPENAI_BASE_URL . 'chat/completions';
    const OPENAI_IMAGE_URL = self::OPENAI_BASE_URL . 'images/generations';
}
