<?php

namespace App\Infrastructure\Symfony\Http\ValueResolver\Press;

use App\Infrastructure\Symfony\Http\DTO\Press\CreatePressNoteRequest;

use function is_a;
use function is_string;

use SplFileInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CreatePressNoteRequestValueResolver implements ValueResolverInterface
{
    /**
     * @return iterable<CreatePressNoteRequest>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();
        if (null === $type || !is_a($type, CreatePressNoteRequest::class, true)) {
            return [];
        }

        $title = $request->request->get('title');
        $subtitle = $request->request->get('subtitle');
        $body = $request->request->get('body');
        $image = $request->files->get('image');

        if (!is_string($title) || !is_string($subtitle) || !is_string($body)) {
            return [];
        }

        if (null === $image || !$image instanceof SplFileInfo) {
            return [];
        }

        return [
            new CreatePressNoteRequest(
                title: $title,
                subtitle: $subtitle,
                body: $body,
                featured: $request->request->getBoolean('featured'),
                image: $image,
            ),
        ];
    }
}
