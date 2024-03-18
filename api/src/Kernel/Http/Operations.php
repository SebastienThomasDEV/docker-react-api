<?php

namespace Api\Framework\Kernel\Http;

use Api\Framework\Kernel\Http\Methods\Delete;
use Api\Framework\Kernel\Http\Methods\Get;
use Api\Framework\Kernel\Http\Methods\Patch;
use Api\Framework\Kernel\Http\Methods\Post;
use Api\Framework\Kernel\Http\Methods\Put;

abstract class Operations
{
    const GET = Get::class;
    const POST = Post::class;
    const PUT = Put::class;
    const PATCH = Patch::class;
    const DELETE = Delete::class;


}