<?php

namespace App\Services;

use App\Services\Interfaces\DietaServiceInterface;

use App\Repositories\Interfaces\DietaRepositoryInterface;
use App\Repositories\Interfaces\ComidaRepositoryInterface;
use Exception;

class DietaService implements DietaServiceInterface
{
    private DietaRepositoryInterface $dietaRepository;
    private ComidaRepositoryInterface $comidaRepository;

    public function __construct(
        DietaRepositoryInterface $dietaRepository,
        ComidaRepositoryInterface $comidaRepository
    ) {
        $this->dietaRepository = $dietaRepository;
        $this->comidaRepository = $comidaRepository;
    }


    /**
     * Crea una nueva dieta con macros nutricionales.
     *
     * @param array $datos Datos de la dieta (nombre, descripcion, id_usuario, id_dato,
     *                     calorias_dieta, proteinas_dieta, grasas_dieta, carbohidratos_dieta, fecha_creacion).
     *
     * @return int ID de la dieta creada.
     *
     * @throws Exception Si faltan datos obligatorios para la creación.
     */

    public function crearDietaConMacros(array $datos): int
    {
        if (
           
            empty($datos['id_usuario']) ||
            empty($datos['id_dato']) ||
            !isset($datos['proteinas_dieta']) ||
            !isset($datos['grasas_dieta']) ||
            !isset($datos['carbohidratos_dieta'])
        ) {
            throw new Exception("Faltan datos obligatorios para crear la dieta.");
        }
            return $this->dietaRepository->createDieta(
                $datos['nombre'] ?? null,
                $datos['descripcion'] ?? null,
                $datos['id_usuario'],
                $datos['id_dato'],
                $datos['calorias_dieta'] ?? null,
                $datos['proteinas_dieta'],
                $datos['grasas_dieta'],
                $datos['carbohidratos_dieta'],
                $datos['fecha_creacion'] ?? null
            );

    }

    /**
     * Actualiza los macros principales de una dieta existente.
     *
     * @param int    $id_dieta    ID de la dieta.
     * @param string $nombre      Nombre de la dieta.
     * @param string $descripcion Descripción de la dieta.
     * @param float  $proteinas   Proteínas totales.
     * @param float  $grasas      Grasas totales.
     * @param float  $carbohidratos Carbohidratos totales.
     *
     * @return array Mensaje de éxito o error.
     */

    public function actualizarMacros(int $id_dieta, string $nombre, string $descripcion, float $proteinas, float $grasas, float $carbohidratos): array
    {
        return $this->dietaRepository->actualizarDieta($id_dieta, $nombre , $descripcion, $proteinas, $grasas, $carbohidratos);
    }

   public function asociarComidas(int $id_dieta, array $comidas): void
    {
        if (!$this->dietaRepository->getDietaById($id_dieta)) {
            throw new Exception("La dieta con ID $id_dieta no existe.");
        }

        foreach ($comidas as $comida) {
            if (is_array($comida)) {
                $id_comida = $comida['id_comida'] ?? null;
            } else {
                $id_comida = $comida;
            }

            $id_comida = (int) $id_comida;

            if (!$id_comida || !$this->comidaRepository->getComidaId($id_comida)) {
                throw new Exception("La comida con ID $id_comida no existe.");
            }

            $this->dietaRepository->asociarComidaDieta($id_dieta, $id_comida);
        }
    }

     /**
     * Elimina una dieta por su ID.
     *
     * @param int $id_dieta ID de la dieta.
     *
     * @return bool True si se eliminó correctamente, False en caso contrario.
     */
    public function eliminarDieta(int $id_dieta): bool{
        return $this->dietaRepository->deleteDieta($id_dieta);
    }

     /**
     * Elimina una dieta por su ID.
     *
     * @param int $id_dieta ID de la dieta.
     *
     * @return bool True si se eliminó correctamente, False en caso contrario.
     */

    public function obtenerPorId(int $id_dieta): ?array{
        return $this->dietaRepository->getDieta($id_dieta);
    }

     /**
     * Verifica si una dieta existe en la base de datos.
     *
     * @param int $id_dieta ID de la dieta.
     *
     * @return bool True si existe, False en caso contrario.
     */

    public function dietaExiste(int $id_dieta): bool{
        return $this->dietaRepository->getDietaById($id_dieta);
    }

    /**
     * Asigna una dieta a un usuario según su rol (Propietario o Preparador).
     *
     * @param int    $id_dieta  ID de la dieta.
     * @param int    $id_usuario ID del usuario.
     * @param string $rol       Rol del usuario.
     *
     * @return array Mensaje de éxito, advertencia o error.
     */

    public function asignarDietaSegunRol(int $id_dieta, int $id_usuario, string $rol): array{
        return $this->dietaRepository->insertDietaRol($id_dieta, $id_usuario, $rol);
    }

    /**
     * Obtiene todas las dietas creadas por un usuario.
     *
     * @param int $id_usuario ID del usuario.
     *
     * @return array[] Lista de dietas.
     */

    public function obtenerDietasPorUsuario(int $id_usuario): array{
        return $this->dietaRepository->getDietasPorUsuario($id_usuario);
    }

    /**
     * Obtiene una dieta junto con su dato asociado.
     *
     * @param int $id_dieta ID de la dieta.
     *
     * @return array Datos de la dieta con información del dato.
     */

    public function obtenerDietaConDato(int $id_dieta): array{
        return $this->dietaRepository->getDietaConDato($id_dieta);
    }

    /**
     * Genera un informe completo de una dieta, incluyendo usuario, comidas, alimentos y roles asociados.
     *
     * @param int $id_dieta ID de la dieta.
     *
     * @return array[] Informe detallado de la dieta.
     *
     * @throws Exception Si la dieta no existe.
     */

    public function obtenerInformeDieta(int $id_dieta): array{
   
        if (!$this->dietaRepository->getDietaById($id_dieta)) {
            throw new \Exception("La dieta con ID $id_dieta no existe.");
        }
        $informe = $this->dietaRepository->getInformeDieta($id_dieta);

        return $informe;
    }

    public function getUltimaDietaCreada(int $id_usuario): array{

       

        $ultima= $this->dietaRepository->getUltimaDietaCreada($id_usuario);

        return $ultima;
    }
}

?>
 