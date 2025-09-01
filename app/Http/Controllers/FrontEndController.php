<?php

namespace App\Http\Controllers;

use App\Models\FrontEnd;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    /**
     * Constructor
     */
    function __construct()
    {
        $this->middleware('permission:front-end-read|front-end-create|front-end-update|front-end-delete', ['only' => ['index','show']]);
        $this->middleware('permission:front-end-create', ['only' => ['create','store']]);
        $this->middleware('permission:front-end-update', ['only' => ['edit','update']]);
        $this->middleware('permission:front-end-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $frontEnds = FrontEnd::all();
        return view('admin-frontend.index', compact('frontEnds'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FrontEnd  $frontEnd
     * @return \Illuminate\Http\Response
     */
    public function edit(FrontEnd $frontEnd)
    {
        $contents = json_decode($frontEnd->content);
        return view('admin-frontend.'.$frontEnd->page, compact('frontEnd', 'contents'));
    }

    /**
     * Update the Home page content.
     *
     * @param Request $request
     * @param FrontEnd $frontEnd
     * @return \Illuminate\Http\Response
     */
    public function updateHome(Request $request, FrontEnd $frontEnd)
    {
        $data = $this->homeValidation($request);

        // Testimonials images (3)
        $i = 0;
        foreach ($request->images as $image) {
            if (isset($request->image_files[$i])) {
                $logoNewName = time().$request->image_files[$i]->getClientOriginalName();
                $request->image_files[$i]->move('lara/front-ends/home', $logoNewName);
                $data['images'][$i] = 'lara/front-ends/home/'.$logoNewName;
            } else {
                $data['images'][$i] = $image;
            }
            $i++;
        }
        unset($data['image_files']);

        // Optional homepage images
        $data['consultationImage'] = $request->input('consultationImage');
        if ($request->hasFile('consultation_image_file')) {
            $logoNewName = time().$request->file('consultation_image_file')->getClientOriginalName();
            $request->file('consultation_image_file')->move('lara/front-ends/home', $logoNewName);
            $data['consultationImage'] = 'lara/front-ends/home/'.$logoNewName;
        }

        $data['specificationImage'] = $request->input('specificationImage');
        if ($request->hasFile('specification_image_file')) {
            $logoNewName = time().$request->file('specification_image_file')->getClientOriginalName();
            $request->file('specification_image_file')->move('lara/front-ends/home', $logoNewName);
            $data['specificationImage'] = 'lara/front-ends/home/'.$logoNewName;
        }

        $data['healthServicesImage'] = $request->input('healthServicesImage');
        if ($request->hasFile('health_services_image_file')) {
            $logoNewName = time().$request->file('health_services_image_file')->getClientOriginalName();
            $request->file('health_services_image_file')->move('lara/front-ends/home', $logoNewName);
            $data['healthServicesImage'] = 'lara/front-ends/home/'.$logoNewName;
        }

        $frontEnd->update(['content' => json_encode($data)]);

        return redirect()->route('front-ends.index')->with('success', trans('Page updated successcully !!'));
    }

    /**
     * updateContact function
     *
     * @param Request $request
     * @param FrontEnd $frontEnd
     * @return \Illuminate\Http\Response
     */
    public function updateContact(Request $request, FrontEnd $frontEnd)
    {
        $data = $this->contactValidation($request);

        // Optional hero image for contact page
        $data['contactHeroImage'] = $request->input('contactHeroImage');
        if ($request->hasFile('contact_hero_image_file')) {
            $logoNewName = time().$request->file('contact_hero_image_file')->getClientOriginalName();
            $request->file('contact_hero_image_file')->move('lara/front-ends/contact', $logoNewName);
            $data['contactHeroImage'] = 'lara/front-ends/contact/'.$logoNewName;
        }

        $frontEnd->update(['content' => json_encode($data)]);

        return redirect()->route('front-ends.index')->with('success', trans('Page updated successcully !!'));
    }

    /**
     * updateContact function
     *
     * @param Request $request
     * @param FrontEnd $frontEnd
     * @return \Illuminate\Http\Response
     */
    public function updateServices(Request $request, FrontEnd $frontEnd)
    {
        $data = $this->serviceValidation($request);
        $i = 0;
        foreach ($request->images as $image) {
            if (isset($request->image_files[$i])) {
                $imageFileUrl = "";
                $logoNewName = time().$request->image_files[$i]->getClientOriginalName();
                $request->image_files[$i]->move('lara/front-ends/service',$logoNewName);
                $imageFileUrl = 'lara/front-ends/service/'.$logoNewName;
                $data['images'][$i] = $imageFileUrl;
            } else {
                $data['images'][$i] = $image;
            }
            $i++;
        }
        unset($data['image_files']);

        // Optional banner image for services page
        $data['servicesBannerImage'] = $request->input('servicesBannerImage');
        if ($request->hasFile('services_banner_image_file')) {
            $logoNewName = time().$request->file('services_banner_image_file')->getClientOriginalName();
            $request->file('services_banner_image_file')->move('lara/front-ends/service', $logoNewName);
            $data['servicesBannerImage'] = 'lara/front-ends/service/'.$logoNewName;
        }

        $frontEnd->update(['content' => json_encode($data)]);

        return redirect()->route('front-ends.index')->with('success', trans('Page updated successcully !!'));
    }

    /**
     * updateContact function
     *
     * @param Request $request
     * @param FrontEnd $frontEnd
     * @return \Illuminate\Http\Response
     */
    public function updateAbout(Request $request, FrontEnd $frontEnd)
    {
        $data = $this->aboutValidation($request);
        $i = 0;
        foreach ($request->images as $image) {
            if (isset($request->image_files[$i])) {
                $imageFileUrl = "";
                $logoNewName = time().$request->image_files[$i]->getClientOriginalName();
                $request->image_files[$i]->move('lara/front-ends/about',$logoNewName);
                $imageFileUrl = 'lara/front-ends/about/'.$logoNewName;

                $data['images'][$i] = $imageFileUrl;
            } else {
                $data['images'][$i] = $image;
            }
            $i++;
        }
        unset($data['image_files']);

        // Optional main image for about page
        $data['aboutMainImage'] = $request->input('aboutMainImage');
        if ($request->hasFile('about_main_image_file')) {
            $logoNewName = time().$request->file('about_main_image_file')->getClientOriginalName();
            $request->file('about_main_image_file')->move('lara/front-ends/about', $logoNewName);
            $data['aboutMainImage'] = 'lara/front-ends/about/'.$logoNewName;
        }

        $frontEnd->update(['content' => json_encode($data)]);

        return redirect()->route('front-ends.index')->with('success', trans('Page updated successcully !!'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FrontEnd  $frontEnd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FrontEnd $frontEnd)
    {
        $i = 0;
        foreach ($request->images as $image) {
            $request->image_files[$i];
            dd($request->image_files[$i]);
            $_POST['images'][$i] = $image->store('front-end');
            $i++;
        }

        $frontEnd->update(['content' => json_encode($_POST)]);
        return redirect('/'.str_replace('home', '', $frontEnd->page));
    }

    /**
     * contact validation function
     *
     * @param Request $request
     * @return array
     */
    public function homeValidation(Request $request)
    {
        return $request->validate([
            'topAddress' => ['required', 'string', 'max:255'],
            'topEmail' => ['required', 'string', 'max:255'],
            'questionCall' => ['required', 'string', 'max:55'],
            'headline' => ['required', 'string', 'max:500'],
            'tagline' => ['required', 'string', 'max:500'],
            'welcome' => ['required', 'string', 'max:500'],
            'welCol1' => ['required', 'string', 'max:500'],
            'welCol2' => ['required', 'string', 'max:500'],
            'caring' => ['required', 'string', 'max:255'],
            'appointmentCount' => ['required', 'string', 'max:500'],
            'clientCount' => ['required', 'string', 'max:500'],
            'caringText' => ['required', 'string', 'max:500'],
            'appointmentText' => ['required', 'string', 'max:500'],
            'clientText' => ['required', 'string', 'max:500'],
            'services' => ['required', 'array', 'size:4'],
            'images' => ['required', 'array', 'size:3'],
            'image_files' => ['nullable', 'array'],
            'image_files.*' => ['image', 'mimes:jpg,jpeg,png'],
            'review' => ['required', 'array', 'size:3'],
            'company' => ['required', 'array', 'size:3'],
            'reviewText' => ['required', 'array', 'size:3'],
            'bottomTagLine' => ['required', 'string', 'max:500'],
            'facebook' => ['required', 'string', 'max:255'],
            'twitter' => ['required', 'string', 'max:255'],
            'google' => ['required', 'string', 'max:255'],
            'monday_s' => ['required', 'string', 'max:255'],
            'tuesday_s' => ['required', 'string', 'max:255'],
            'wednesday_s' => ['required', 'string', 'max:255'],
            'thursday_s' => ['required', 'string', 'max:255'],
            'friday_s' => ['required', 'string', 'max:255'],
            'saturday_s' => ['required', 'string', 'max:255'],
            'sunday_s' => ['required', 'string', 'max:255'],
            // New optional editable assets
            'videoEmbedUrl' => ['nullable', 'string', 'max:1000'],
            'consultationImage' => ['nullable', 'string', 'max:1000'],
            'specificationImage' => ['nullable', 'string', 'max:1000'],
            'healthServicesImage' => ['nullable', 'string', 'max:1000'],
            'consultation_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
            'specification_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
            'health_services_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ]);
    }

    /**
     * contact validation function
     *
     * @param Request $request
     * @return array
     */
    private function contactValidation(Request $request)
    {
        return $request->validate([
            'contactAddress' => ['required', 'string', 'max:255'],
            'contactPhone' => ['required', 'string', 'max:255'],
            'contactMail' => ['required', 'string', 'max:255'],
            'contactGoogleMap' => ['required', 'url', 'max:500'],
            // Optional hero image
            'contactHeroImage' => ['nullable', 'string', 'max:1000'],
            'contact_hero_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ]);
    }

    /**
     * service validation function
     *
     * @param Request $request
     * @return array
     */
    private function serviceValidation(Request $request)
    {
        return $request->validate([
            'serviceText' => ['required', 'string', 'max:500'],
            'feature' => ['required', 'string', 'max:500'],
            'check' => ['required', 'string', 'max:500'],
            'open' => ['required', 'string', 'max:500'],
            'smile' => ['required', 'string', 'max:500'],
            'work' => ['required', 'string', 'max:500'],
            'images' => ['required', 'array', 'size:6'],
            'image_files' => ['nullable', 'array'],
            'image_files.*' => ['image', 'mimes:jpg,jpeg,png'],
            'serviceName' => ['required', 'array', 'size:6'],
            'serviceDescription' => ['required', 'array', 'size:6'],
            // Optional banner image
            'servicesBannerImage' => ['nullable', 'string', 'max:1000'],
            'services_banner_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ]);
    }

    public function aboutValidation(Request $request)
    {
        return $request->validate([
            'aboutAnnualCheck' => ['required', 'string', 'max:500'],
            'aboutWorkHeart' => ['required', 'string', 'max:500'],
            'aboutHelpHand' => ['required', 'string', 'max:500'],
            'aboutWhyChooseUs' => ['required', 'string', 'max:500'],
            'aboutOurTeam' => ['required', 'string', 'max:500'],
            'teams' => ['required', 'array', 'size:3'],
            'teamPost' => ['required', 'array', 'size:3'],
            'images' => ['required', 'array', 'size:3'],
            'image_files' => ['nullable', 'array'],
            'image_files.*' => ['image', 'mimes:jpg,jpeg,png'],
            // Optional main image
            'aboutMainImage' => ['nullable', 'string', 'max:1000'],
            'about_main_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ]);
    }
}
