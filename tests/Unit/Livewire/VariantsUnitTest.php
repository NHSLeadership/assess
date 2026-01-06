<?php

use App\Livewire\Variants;
use Illuminate\Database\Eloquent\Collection;
class VariantsValidationFake
{
    public array $data = [];

    public function validateVariants(Collection $attributes): bool
    {
        $errors = [];

        foreach ($attributes as $key => $attribute) {
            if (!array_key_exists($key, $this->data) || empty($this->data[$key])) {
                $errors[] = $key;
            }
        }

        return empty($errors);
    }
}

it('fails validation when required fields are missing', function () {
    $validator = new VariantsValidationFake();

    $attributes = new Collection([
        'stage' => (object)['key' => 'stage', 'label' => 'Stage'],
    ]);

    $validator->data = []; // missing

    expect($validator->validateVariants($attributes))->toBeFalse();
});

it('passes validation when required fields are present', function () {
    $validator = new VariantsValidationFake();

    $attributes = new Collection([
        'stage' => (object)['key' => 'stage', 'label' => 'Stage'],
    ]);

    $validator->data = ['stage' => '2'];

    expect($validator->validateVariants($attributes))->toBeTrue();
});
