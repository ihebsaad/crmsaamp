<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\Consultation;

class EmailTemplateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $templates = EmailTemplate::where('user',auth()->id())->get();
        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Liste de emails templates"]);

        return view('email_templates.index', compact('templates'));
    }

    public function create()
    {
        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Création email template"]);

        return view('email_templates.edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required',
        ]);

        EmailTemplate::create($request->all());

        //return redirect()->route('email-templates.index')->with('success', 'Template créé avec succès.');
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required',
        ]);

        EmailTemplate::create($request->all());

        return redirect()->route('email-templates.index')->with('success', 'Template créé avec succès.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Modification email template"]);

        return view('email_templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required',
        ]);

        $emailTemplate->update($request->all());

        return redirect()->route('email-templates.index')->with('success', 'Template mis à jour avec succès.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return back()->with('success', 'Template supprimé avec succès.');
    }

}
