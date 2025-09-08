<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Programa>
 */
class ProgramaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dataInicio = $this->faker->dateTimeBetween('-1 year', 'now');
        $dataFim = $this->faker->dateTimeBetween($dataInicio, '+1 year');

       return [
             'nome' => $this->faker->sentence(3), // nome do programa
            'descricao' => $this->faker->paragraph(), 
            'data_inicio' => $dataInicio->format('Y-m-d'),
            'data_fim' => $dataFim->format('Y-m-d'),
            'estado' => $this->faker->randomElement(['activo', 'pendente']), 
        ];
    }
}
