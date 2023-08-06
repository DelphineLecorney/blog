<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Illuminate\Support\Facades\File;

class Post
{
    public $title;
    public $excerpt;
    public $date;
    public $body;
    public $slug;

    public function __construct($title, $excerpt, $date, $body, $slug)
    {
        $this->title = $title;
        $this->excerpt = $excerpt;
        $this->date = $date;
        $this->body = $body;
        $this->slug = $slug;
    }

    public static function all()
    {       // Get a list of files from the "posts" directory using Laravel's File facade
            return collect(File::files(resource_path("posts")))
            // Parse YAML front matter for each file and create an array of objects
                ->map(fn($file) =>YamlFrontMatter::parseFile($file))
                // Convert the parsed YAML documents to a collection of Post objects
                ->map(fn($document) => new Post(
                        $document->title,
                        $document->excerpt,
                        $document->date,
                        $document->body(),
                        $document->slug
                ))
                ->sortBy('date');
    }

    public static function find($slug)
    {
        // Return the found Post object or null if not found
        // Get all the Post objects from the collection returned by the 'all()' method
        // Find the first Post object in the collection where the 'slug' property matches the provided $slug
        return static::all()->firstWhere('slug', $slug);
    }
}
