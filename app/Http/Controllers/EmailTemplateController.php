<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailTemplateRequest;
use App\Http\Requests\UpdateEmailTemplateRequest;
use App\Models\EmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the email templates.
     */
    public function index(): View
    {
        return view('email-templates.index', [
            'emailTemplates' => EmailTemplate::orderBy('name')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new email template.
     */
    public function create(): View
    {
        return view('email-templates.create');
    }

    /**
     * Store a newly created email template in storage.
     */
    public function store(StoreEmailTemplateRequest $request): RedirectResponse
    {
        EmailTemplate::create($request->validated());

        return redirect()->route('email-templates.index')->with('status', 'Šablóna e-mailu bola úspešne vytvorená.');
    }

    /**
     * Show the form for editing the specified email template.
     */
    public function edit(EmailTemplate $emailTemplate): View
    {
        return view('email-templates.edit', [
            'emailTemplate' => $emailTemplate,
        ]);
    }

    /**
     * Update the specified email template in storage.
     */
    public function update(UpdateEmailTemplateRequest $request, EmailTemplate $emailTemplate): RedirectResponse
    {
        $emailTemplate->update($request->validated());

        return redirect()->route('email-templates.index')->with('status', 'Šablóna e-mailu bola úspešne upravená.');
    }

    /**
     * Remove the specified email template from storage.
     */
    public function destroy(EmailTemplate $emailTemplate): RedirectResponse
    {
        $emailTemplate->delete();

        return redirect()->route('email-templates.index')->with('status', 'Šablóna e-mailu bola úspešne odstránená.');
    }
}
