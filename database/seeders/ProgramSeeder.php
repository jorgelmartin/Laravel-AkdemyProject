<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('programs')->insert([
        [
            'name' => 'Javascript',
            'surname' => 'Adéntrate en el dinámico mundo de JavaScript, con su versatilidad y capacidad para interactuar con el navegador, JavaScript permite crear experiencias web cautivadoras y en tiempo real. Desde interactividad hasta animaciones y desarrollo de aplicaciones complejas, JavaScript se convierte en la piedra angular del frontend y backend. Únete a nuestra comunidad de desarrolladores y desata todo el potencial de JavaScript para llevar tus proyectos web al siguiente nivel. ¡Embárcate en esta emocionante travesía para definir el futuro digital con tus habilidades en JavaScript!',
            'price' => '570',
            'image' => 'images/javascript.png',
        ],
        [
            'name' => 'PHP',
            'description' => 'Descubre el poder de PHP, el corazón de las aplicaciones web modernas. Con PHP, podrás construir servidores robustos y escalables, gestionando datos y lógica de forma eficiente. Desde manejar bases de datos hasta crear APIs sólidas, PHP te brinda el control total sobre la funcionalidad y rendimiento de tus aplicaciones. Únete a nuestra formación especializada y desbloquea todo el potencial de PHP para llevar tus habilidades en el backend al siguiente nivel. ¡Prepárate para crear una experiencia web excepcional!',
            'price' => '550',
            'image' => 'images/php.png',
        ],
        [
            'name' => 'Python',
            'description' => 'Descubre Python, el lenguaje que enamora a millones de desarrolladores en todo el mundo. Con su sintaxis elegante y versatilidad, resolver problemas complejos será sencillo y emocionante. Desde IA hasta desarrollo web, Python tiene todo lo que necesitas para crear proyectos asombrosos. ¡Únete a nuestra academia y desata tu creatividad sin límites!',
            'price' => '580',
            'image' => 'images/python.png',
        ],
        [
            'name' => 'Ciberseguridad',
            'description' => 'Adéntrate en el apasionante mundo de la ciberseguridad, donde la protección y defensa de datos se convierte en una misión crucial. Descubre técnicas de vanguardia para salvaguardar sistemas y redes contra amenazas cibernéticas. Conviértete en un guardián digital experto, protegiendo información valiosa y manteniendo a salvo el mundo conectado. ¡Únete a nuestra academia y prepárate para defender con éxito el futuro digital!',
            'price' => '700',
            'image' => 'images/ciberseguridad.png',
        ],
        ]);
    }
}
