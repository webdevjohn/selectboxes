# Select Boxes 

## Introduction
The Select Boxes package was created to provide an easy way to create the data used in select boxes on HTML forms.   The package also helps dry-up your code by encapsulating select box data within dedicated re-usable classes.


### Example Problem
It can be a pain when dealing with forms that require multiple select boxes, as the select boxes that are defined in the `create()` method also have to be re-defined within the `edit()` method of a controller.  With the example below, you would also need to import 5 eloquent models: `Artist, Genre, Label, Format, Tag`.  This starts to become messey.  


```
use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Label;
use App\Models\Tag;

public function create()
{
    return View('cms.basedata.tracks.create', [
        'selectBoxes' => [

            'artistList' => Artist::orderBy('artist_name')
                ->pluck('artist_name', 'id') 
                ->toArray(),            

            'genreList' => array_merge(
                [0 => 'Please Select...'], 
                Genre::orderBy('genre')->pluck('genre', 'id')->toArray()
            ),

            'labelList' => array_merge(
                [0 => 'Please Select...'], 
                Label::orderBy('label')->pluck('label', 'id')->toArray()
            ),

            'formatList' => array_merge(
                [0 => 'Please Select...'], 
                Format::orderBy('format')->pluck('format', 'id')->toArray(),
            ),

            'tagList' => Tag::orderBy('tag')->pluck('tag', 'id')
        ]
    ]);
}
```

### Example Solution
The above example can be refactored to the following when using the Select Boxes package:

```
// The model import use statements are no longer needed.

public function create()
{
    return View('cms.basedata.tracks.create', [
        'selectBoxes' => $this->selectBoxes->get(),
    ]);
}
```


## Installation
The package can be installed via composer using the following command: 
```
composer require webdevjohn/selectboxes
```

## Usage
You can create a select box group, or use the ```SelectBoxService``` directly.

### Creating a Select Box Group
Create a select box group when multiple select boxes are needed on a form.  This can help DRY up your code, as the select box group can be re-used when creating and updating records (e.g. the ```create()``` and ```edit()``` methods of a controller).

Run the following artisan command to create a select box group:
```
php artisan group:make {name}
```

By default, the newly created group will created in:
```
App\Services\SelectBoxes\Groups
```

You can override the default namespace by specifying an optional 2nd argument when creating a group: 
```
php artisan group:make {name} {namespace}
```
#### Defining Select Boxes
```
namespace App\Services\SelectBoxes\Groups;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Label;
use App\Models\Tag;
use Webdevjohn\SelectBoxes\SelectBoxService;

class DemoSelectBoxGroup extends SelectBoxService 
{
    public function get(): array
    {
         return  [
            'artistList' => $this->createFrom(Artist::class)    
                ->display('artist_name')
                ->orderBy('artist_name')
                ->asArray(placeHolder: false),

            'genreList' => $this->createFrom(Genre::class)
                ->display('genre')       
                ->orderBy('genre')
                ->asArray(),

            'labelList' => $this->createFrom(Label::class)
                ->display('label')
                ->orderBy('label')
                ->asArray(),  

            'formatList' => $this->createFrom(Format::class)
                ->display('format')
                ->orderBy('format')    
                ->asArray(),
                
            'tagList' => $this->createFrom(Tag::class)
                ->display('tag')
                ->orderBy('tag')
                ->asArray(placeHolder: false),
        ];
    }
}
```
#### Available Methods

| Method | Method Type | Arguments | Returns |
| --- | --- | --- | --- |
| createFrom() | Mandatory | $model **string** - fully qualified class name | SelectBoxService |
| display() | Mandatory | $optionText **string** - the text that is displayed for a select box option <br /> $optionValue **string** - the value of a select box option (default = "**id**") | SelectBoxService |
| where() | Optional | $column **string** - name of the column to filter <br /> $operator **string** - the comparison operator <br /> $value **string** - the value | SelectBoxService |
| orderBy() | Optional | $orderBy **string** - name of the column to order by <br /> $sortOrder **string** - sort order (default = "**desc**") | SelectBoxService |
| asArray() | Optional* | $placeHolder **bool** - include a placeholder (default = "**true**") <br /> $placeHolderText **string** - (default = "**Please Select....**") | Array |
| asJson() | Optional* | $placeHolder **bool** - include a placeholder (default = "**true**") <br /> $placeHolderText **string** - (default = "**Please Select....**") | String (JSON) |


*note. either `asArray()` or `asJson()` must be called in order to return results.

### Using a Select Box Group
You can use constructor or method injection to inject a Select Box Group. 

```
public function __construct(
    protected DemoSelectBoxGroup $selectBoxes
) {}
```

Then use the `get()` method on the `$selectBoxes` instance variable to create the select boxes.

```
public function create()
{
    return View('cms.basedata.tracks.create', [
        'selectBoxes' => $this->selectBoxes->get(),
    ]);
}
```

This will push the data to the view in the following format:
```
array:1 [▼
  "selectBoxes" => array:5 [▼
    "artistList" => array:1378 [▶]
    "genreList" => array:12 [▶]
    "labelList" => array:484 [▶]
    "formatList" => array:4 [▶]
    "tagList" => array:26 [▶]
  ]
]
```

##### Acessing Select Boxes Within a Blade File
```
<label for="genre_id">Genre: </label>	
<select name="genre_id">				
    @foreach($selectBoxes['genreList'] as $key => $value)				
        <option value="{{$key}}" {{ (old('genre_id') == $key) ? "selected='selected" : ""}}>{{ $value }}</option>
    @endforeach
</select>
```