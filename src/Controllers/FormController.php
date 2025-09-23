<?php

declare(strict_types=1);

namespace Mk\Form\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Controller;
use Mk\Form\Services\FormService;

/**
 * Form Controller
 *
 * Handles HTTP requests for form operations including listing and retrieving forms.
 */
class FormController extends Controller
{
    public function __construct(
        protected FormService $formService
    ) {}

    /**
     * Get a list of all registered forms.
     */
    public function index(): JsonResponse
    {
        $forms = $this->formService->getAllForms();

        return Response::json([
            'forms' => $forms,
            'count' => count($forms),
        ]);
    }

    /**
     * Get details for a specific form.
     */
    public function getForm(string $formName): JsonResponse
    {
        try {
            $form = $this->formService->getForm($formName);
            return Response::json($form->toArray());
        } catch (\InvalidArgumentException $e) {
            return Response::json([
                'error' => 'Form not found',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Submit a form.
     */
    public function submit(Request $request, string $formName): JsonResponse
    {
        $result = $this->formService->processFormSubmission($formName, $request);
        $status = ($result['success'] ?? false) ? 200 : 422;
        return Response::json($result, $status);
    }
}
