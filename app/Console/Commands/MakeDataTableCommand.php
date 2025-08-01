<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDataTableCommand extends Command
{
    protected $signature = 'make:datatable {name} {model} {--fields=*} {--sortable=*} {--searchable=*}';
    
    protected $description = 'Create a new Livewire data table component with sorting and searching functionality';

    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->argument('model');
        $fields = $this->parseFieldOption($this->option('fields'));
        $sortableFields = $this->parseFieldOption($this->option('sortable'));
        $searchableFields = $this->parseFieldOption($this->option('searchable'));

        // Generate component and view files
        $this->createLivewireComponent($name, $model, $fields, $sortableFields, $searchableFields);
        $this->createBladeView($name, $fields, $sortableFields);

        $this->info("Data table component '{$name}' created successfully!");
        $this->info("Component: app/Livewire/{$name}.php");
        $this->info("View: resources/views/livewire/" . Str::kebab($name) . ".blade.php");
    }
    
    protected function parseFieldOption($option)
    {
        if (empty($option)) {
            return [];
        }
        
        if (is_array($option)) {
            // If passed as array, flatten it and explode any comma-separated values
            $fields = [];
            foreach ($option as $field) {
                $fields = array_merge($fields, explode(',', $field));
            }
            return array_map('trim', $fields);
        }
        
        return array_map('trim', explode(',', $option));
    }

    protected function createLivewireComponent($name, $model, $fields, $sortableFields, $searchableFields)
    {
        $componentPath = app_path("Livewire/{$name}.php");
        
        // Ensure directory exists
        File::ensureDirectoryExists(dirname($componentPath));

        $stub = $this->getLivewireStub();
        $content = $this->replacePlaceholders($stub, [
            'ClassName' => $name,
            'ModelName' => $model,
            'ModelVariable' => Str::camel(Str::plural($model)),
            'DefaultSortField' => !empty($sortableFields) ? $sortableFields[0] : (!empty($fields) ? $fields[0] : 'id'),
            'SearchableFields' => $this->generateSearchableFields($searchableFields),
            'ViewName' => 'livewire.' . Str::kebab($name),
        ]);

        File::put($componentPath, $content);
    }

    protected function createBladeView($name, $fields, $sortableFields)
    {
        $viewPath = resource_path('views/livewire/' . Str::kebab($name) . '.blade.php');
        
        // Ensure directory exists
        File::ensureDirectoryExists(dirname($viewPath));

        $stub = $this->getBladeStub();
        $content = $this->replacePlaceholders($stub, [
            'TableHeaders' => $this->generateTableHeaders($fields, $sortableFields),
            'TableBody' => $this->generateTableBody($fields),
            'ModelVariable' => Str::camel(Str::plural($this->argument('model'))),
        ]);

        File::put($viewPath, $content);
    }

    protected function getLivewireStub()
    {
        return '<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\{{ModelName}};

class {{ClassName}} extends Component
{
    use WithPagination;
    
    #[Url(except: \'\')]
    public $search = \'\';
    
    public $perPage = 10;
    
    #[Url(except: \'{{DefaultSortField}}\')]
    public $sortBy = \'{{DefaultSortField}}\';
    
    #[Url(except: \'asc\')]
    public $sortDirection = \'asc\';
    
    protected $queryString = [
        \'search\' => [\'except\' => \'\'],
        \'sortBy\' => [\'except\' => \'{{DefaultSortField}}\'],
        \'sortDirection\' => [\'except\' => \'asc\'],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function sortByColumn($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === \'asc\' ? \'desc\' : \'asc\';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = \'asc\';
        }
        
        $this->resetPage();
    }
    
    public function render()
    {
        ${{ModelVariable}} = {{ModelName}}::query()
            {{SearchableFields}}
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view(\'{{ViewName}}\', compact(\'{{ModelVariable}}\'));
    }
}';
    }

    protected function getBladeStub()
    {
        return '<div>
  <div class="flex justify-between mb-4">
    <div></div>
    <div class="relative w-72">
      <input type="text" wire:model.live.debounce.100ms="search"
        class="w-full px-4 py-2 border border-[rgba(223,223,223,0.8)] rounded-md pr-10"
        placeholder="Search...">
      <div class="absolute inset-y-0 right-0 flex items-center pr-3">
        <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
            stroke="#7B7B7B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
    </div>
  </div>

  <div class="border border-[rgba(223,223,223,0.8)] rounded-lg overflow-hidden">
    <table class="min-w-full">
      <thead>
        <tr class="bg-white">
          {{TableHeaders}}
        </tr>
      </thead>
      <tbody>
        @forelse(${{ModelVariable}} as $item)
          <tr class="border-t border-[rgba(223,223,223,0.25)]">
            {{TableBody}}
          </tr>
        @empty
          <tr>
            <td colspan="100%" class="py-8 px-4 text-center text-gray-500">
              No data found
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ ${{ModelVariable}}->links() }}
  </div>

  @if (session()->has(\'message\'))
    <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50"
      x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
      {{ session(\'message\') }}
    </div>
  @endif
</div>';
    }

    protected function generateSearchableFields($searchableFields)
    {
        if (empty($searchableFields)) {
            return '';
        }

        $conditions = [];
        foreach ($searchableFields as $index => $field) {
            if ($index === 0) {
                $conditions[] = "->where('{$field}', 'like', '%' . \$this->search . '%')";
            } else {
                $conditions[] = "->orWhere('{$field}', 'like', '%' . \$this->search . '%')";
            }
        }

        $allConditions = implode("\n                      ", $conditions);

        return "\n            ->when(\$this->search, function(\$query) {\n                \$query->where(function(\$q) {\n                    \$q" . $allConditions . ";\n                });\n            })";
    }

    protected function generateTableHeaders($fields, $sortableFields)
    {
        $headers = [];
        
        foreach ($fields as $field) {
            $label = Str::title(str_replace('_', ' ', $field));
            
            if (in_array($field, $sortableFields)) {
                $headers[] = "          <th class=\"py-3 px-4 text-left\">
            <button wire:click=\"sortByColumn('{$field}')\"
              class=\"flex items-center text-xs text-black hover:text-blue-600 transition-colors\">
              <span>{$label}</span>
              @if (\$sortBy === '{$field}')
                @if (\$sortDirection === 'asc')
                  <svg class=\"w-3 h-3 ml-1\" fill=\"currentColor\" viewBox=\"0 0 20 20\">
                    <path d=\"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\" />
                  </svg>
                @else
                  <svg class=\"w-3 h-3 ml-1\" fill=\"currentColor\" viewBox=\"0 0 20 20\">
                    <path d=\"M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z\" />
                  </svg>
                @endif
              @else
                <svg class=\"w-3 h-3 ml-1 opacity-30\" fill=\"currentColor\" viewBox=\"0 0 20 20\">
                  <path d=\"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\" />
                </svg>
              @endif
            </button>
          </th>";
            } else {
                $headers[] = "          <th class=\"py-3 px-4 text-left text-xs text-black\">{$label}</th>";
            }
        }

        return implode("\n", $headers);
    }

    protected function generateTableBody($fields)
    {
        $cells = [];
        
        foreach ($fields as $field) {
            $cells[] = "            <td class=\"py-2 px-4 text-xs\">{{ \$item->{$field} }}</td>";
        }

        return implode("\n", $cells);
    }

    protected function replacePlaceholders($content, $replacements)
    {
        foreach ($replacements as $placeholder => $value) {
            $content = str_replace('{{' . $placeholder . '}}', $value, $content);
        }

        return $content;
    }
}