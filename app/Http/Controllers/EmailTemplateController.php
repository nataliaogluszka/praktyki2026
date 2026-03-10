<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailTemplateController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
        ]);

        EmailTemplate::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'subject' => $validated['subject'],
            'content' => '<p>Nowy szablon.</p>', 
        ]);

        return back()->with('success', 'Szablon został utworzony.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('email_templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $emailTemplate->update($request->only(['subject', 'content', 'name']));

        return redirect()->route('settings.index')->with('success', 'Szablon zaktualizowany.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();
        return back()->with('success', 'Szablon został usunięty.');
    }
}