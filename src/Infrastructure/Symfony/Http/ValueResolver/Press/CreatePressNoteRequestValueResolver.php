<?php

namespace App\Infrastructure\Symfony\Http\ValueResolver\Press;

use App\Infrastructure\Symfony\Http\DTO\Press\CreatePressNoteRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CreatePressNoteRequestValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();
        if (!is_a($type, CreatePressNoteRequest::class, true)) {
            return [];
        }

        return [
            new CreatePressNoteRequest(
                title: $request->request->get('title'),
                subtitle: $request->request->get('subtitle'),
                body: $request->request->get('body'),
                featured: $request->request->getBoolean('featured'),
                image: $request->files->get('image'),
            ),
        ];
    }
}
