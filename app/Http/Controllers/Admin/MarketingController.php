<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Promotion;
use App\Models\Prompt;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Review;

class MarketingController extends Controller
{
    /**
     * Display the Marketing management page with quotes and promotions.
     */
    public function showMarketingPage()
    {
        $quotes = Quote::orderBy('created_at', 'desc')->paginate(10);
        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(10);
        $prompts = Prompt::orderBy('created_at', 'desc')->paginate(10);
        
        $response = Inertia::render('Admin/Marketing/Index', [
            'quotes' => $quotes,
            'promotions' => $promotions,
            'prompts' => $prompts,
        ]);

        // $response->toResponse(request())->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        // $response->toResponse(request())->header('Pragma', 'no-cache');
        // $response->toResponse(request())->header('Expires', '0');

        return $response;
    }

    /**
     * Store a new quote.
     */
    public function storeQuote(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

        Quote::create([
            'content' => $request->content,
            'author' => $request->author,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.marketing.index')->with('success', 'Quote created successfully.');
    }

    /**
     * Update the specified quote.
     */
    public function updateQuote(Request $request, Quote $quote)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $quote->update([
            'content' => $request->content,
            'author' => $request->author,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.marketing.index')->with('success', 'Quote updated successfully.');
    }

    /**
     * Remove the specified quote from storage.
     */
    public function destroyQuote(Quote $quote)
    {
        $quote->delete();
        return redirect()->route('admin.marketing.index')->with('success', 'Quote deleted successfully.');
    }

    /**
     * Toggle the active status of the specified quote.
     */
    public function toggleQuoteStatus(Quote $quote)
    {
        $quote->is_active = !$quote->is_active;
        $quote->save();
        
        return redirect()->back()->with('success', 'Quote status updated successfully.');
    }

    /**
     * Store a new promotion.
     */
    public function storePromotion(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'promotion_type' => 'required|in:text,poster',
            'text_content' => 'nullable|string|max:1000',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'till_date' => 'nullable|date',
        ]);

        $imagePath = null;
        if ($request->promotion_type === 'poster' && $request->hasFile('poster_image')) {
            $file = $request->file('poster_image');
            $fileName = time() . '_promotion.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/promotions/posters'), $fileName);
            $imagePath = 'uploads/promotions/posters/' . $fileName;
        }

        Promotion::create([
            'title' => $validated['title'],
            'promotion_type' => $validated['promotion_type'],
            'text_content' => $validated['text_content'] ?? null,
            'image_path' => $imagePath,
            'is_active' => $validated['is_active'] ?? false,
            'till_date' => $validated['till_date'] ?? null,
        ]);

        return redirect()->route('admin.marketing.index')->with('success', 'Promotion created successfully.');
    }

    /**
     * Update the specified promotion.
     */
    public function updatePromotion(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'promotion_type' => 'required|in:text,poster',
            'text_content' => 'nullable|string|max:1000',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'sometimes|boolean',
            'till_date' => 'nullable|date',
        ]);

        $imagePath = $promotion->image_path;
        if ($request->promotion_type === 'poster' && $request->hasFile('poster_image')) {
            if ($imagePath && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }
            $file = $request->file('poster_image');
            $fileName = time() . '_promotion.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/promotions/posters'), $fileName);
            $imagePath = 'uploads/promotions/posters/' . $fileName;
        } elseif ($request->promotion_type === 'text' && $imagePath) {
            if (file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }
            $imagePath = null;
        }

        $promotion->update([
            'title' => $validated['title'],
            'promotion_type' => $validated['promotion_type'],
            'text_content' => $validated['text_content'] ?? null,
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active'),
            'till_date' => $validated['till_date'] ?? $promotion->till_date,
        ]);

        return redirect()->route('admin.marketing.index')->with('success', 'Promotion updated successfully.');
    }

    /**
     * Remove the specified promotion from storage.
     */
    public function destroyPromotion(Promotion $promotion)
    {
        if ($promotion->image_path && file_exists(public_path($promotion->image_path))) {
            unlink(public_path($promotion->image_path));
        }
        $promotion->delete();
        return redirect()->route('admin.marketing.index')->with('success', 'Promotion deleted successfully.');
    }

    /**
     * Toggle the active status of the specified promotion.
     */
    public function togglePromotionStatus(Promotion $promotion)
    {
        $promotion->is_active = !$promotion->is_active;
        $promotion->save();

        return redirect()->back()->with('success', 'Promotion status updated successfully.');
    }

    /**
     * Get a random active and unexpired promotion.
     */
    public function getRandomActivePromotion(Request $request)
    {
        Log::info('Attempting to fetch a random active promotion.');

        $query = Promotion::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('till_date')
                      ->orWhere('till_date', '>=', now()->toDateString());
            });

        $count = $query->count();
        Log::info("Found {$count} active and valid promotions.");

        $promotion = $query->inRandomOrder()->first();

        if ($promotion) {
            Log::info('Returning promotion.', ['promotion_id' => $promotion->id]);
        } else {
            Log::warning('No active promotion found.');
        }

        return response()->json($promotion ?? []);
    }


    /**
     * Store a new prompt.
     */
    public function storePrompt(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'prompt_text' => 'required|string',
            'target_audience' => 'required|in:students,instructors,all',
            'trigger_condition' => 'required|in:daily,weekly',
            'frequency' => 'required',
            'times_per_day' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if ($validated['trigger_condition'] === 'daily' && is_array($validated['frequency'])) {
            $validated['frequency'] = json_encode($validated['frequency']);
        }

        Prompt::create($validated);

        return redirect()->route('admin.marketing.index')->with('success', 'Prompt created successfully.');
    }

    /**
     * Update the specified prompt.
     */
    public function updatePrompt(Request $request, Prompt $prompt)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'prompt_text' => 'required|string',
            'target_audience' => 'required|in:students,instructors,all',
            'trigger_condition' => 'required|in:daily,weekly',
            'frequency' => 'required',
            'times_per_day' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validated['trigger_condition'] === 'daily' && is_array($validated['frequency'])) {
            $validated['frequency'] = json_encode($validated['frequency']);
        }
        
        $updateData = $validated;
        $updateData['is_active'] = $request->boolean('is_active');

        $prompt->update($updateData);

        return redirect()->route('admin.marketing.index')->with('success', 'Prompt updated successfully.');
    }

    /**
     * Remove the specified prompt from storage.
     */
    public function destroyPrompt(Prompt $prompt)
    {
        $prompt->delete();
        return redirect()->route('admin.marketing.index')->with('success', 'Prompt deleted successfully.');
    }

    /**
     * Toggle the active status of the specified prompt.
     */
    public function togglePromptStatus(Prompt $prompt)
    {
        $prompt->is_active = !$prompt->is_active;
        $prompt->save();
        
        return redirect()->back()->with('success', 'Prompt status updated successfully.');
    }
}
