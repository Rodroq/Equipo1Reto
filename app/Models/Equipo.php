<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 *@OA\Schema(
 *  schema="Equipo",
 *  type="object",
 *  title="Equipo",
 *  required={"nombre", "jugadores"},
 *  @OA\Property(property="nombre", type="string", example="Desguace FC"),
 *  @OA\Property(property="grupo", type="string", example="A"),
 *  @OA\Property(property="centro", type="object", ref="#/components/schemas/Centro"),
 *  @OA\Property(property="jugadores", type="array", @OA\Items(ref="#/components/schemas/Jugador")),
 *)
 */

class Equipo extends Model
{
    protected $table = 'equipos';

    protected $fillable = [
        'nombre',
        'grupo',
        'usuarioIdCreacion',
        'fechaCreacion',
        'usuarioIdActualizacion',
        'fechaActualizacion',
        'centro_id'
    ];

    /* protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->usuarioIdCreacion = Auth::user()->id;
            $model->fechaCreacion = now();
        });

        static::updating(function ($model) {
            $model->usuarioIdActualizacion = Auth::user()->id;
            $model->fechaActualizacion = now();
        });
    } */

    /**
     * Crea múltiples jugadores relacionados con el equipo.
     *
     * @param array $jugadores Un array de datos de jugadores (pueden ser arrays con los campos necesarios para crear un Jugador).
     * @return void
     */
    public function crearJugadores($jugadores)
    {
        $this->jugadores()->createMany(
            collect($jugadores)->map(function ($jugador) {
                if (!array_key_exists('ciclo', $jugador)) {
                    return $jugador;
                }

                $ciclo_id = Ciclo::where('nombre', $jugador['ciclo'])->first()->id;
                $estudio_id = Estudio::where('ciclo_id', $ciclo_id)->first()->id;
                $jugador['estudio_id'] = $estudio_id;

                return $jugador;
            })
        );
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class, 'equipoL')
            ->orWhere('equipoV');
    }

    public function inscripciones()
    {
        return $this->hasOne(Inscripcion::class);
    }

    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class);
    }

    public function imagenes()
    {
        return $this->hasMany(Imagen::class);
    }

    public function patrocinadores()
    {
        return $this->belongsToMany(Patrocinador::class);
    }

    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }
}
