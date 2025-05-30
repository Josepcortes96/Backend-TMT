<?php
    namespace App\Controllers;

    use App\Services\Interfaces\ComidaServiceInterface;

    class ComidaController {
        public function __construct(private ComidaServiceInterface $service) {}

        public function crear() {
            try {
                $json = file_get_contents("php://input");
                $datos = json_decode($json, true);
                $result = $this->service->crearComidasConAlimentos($datos);
                echo json_encode(['status' => 'success', 'data' => $result]);
            } catch (\Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }

        public function agregar() {
            try {
                $json = file_get_contents("php://input");
                $datos = json_decode($json, true);
                $result = $this->service->agregarAlimentosAComida($datos);
                echo json_encode(['status' => 'success', 'data' => $result]);
            } catch (\Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }
?>