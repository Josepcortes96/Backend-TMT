<?php

    namespace App\Services;

    use App\Models\User;
    use App\Repositories\Interfaces\UserRepositoryInterface;
    use App\Services\Interfaces\UserServiceInterface;
 

    class UserService implements UserServiceInterface{
            
        private UserRepositoryInterface $userRepository;

        public function __construct(UserRepositoryInterface $userRepository) {
            $this->userRepository = $userRepository;
        }

        public function createUser(User $user): int {
            $this->validarFormatoPassword($user->password); // ⬅️ validación

            $user->password = password_hash($user->password, PASSWORD_DEFAULT); // ⬅️ solo aquí se hashea

            return $this->userRepository->create($user); // el repositorio ya no vuelve a hashear
        }

        public function getAll(): array {
            return $this->userRepository->read();
        }

        public function getOne(int $id): array {
            return $this->userRepository->getUser($id);
        }

        public function getUserName(string $nombre): array {
            return $this->userRepository->getUserName($nombre);
        }

        public function updateUser(int $id, User $user): void {
            $rolActual = $this->userRepository->getRol($id);

            if ($rolActual !== $user->rol) {
                $centroAnterior = $this->userRepository->capturarCentroId($id, $rolActual);
                $dietaAnterior = $this->userRepository->capturarDietaId($id, $rolActual);

                $this->userRepository->eliminarUsuarioTabla($id, $rolActual);
                $this->userRepository->eliminarRelacionCentroRol($id, $rolActual);
                $this->userRepository->eliminarRelacionDietaRol($id, $rolActual);

                $this->userRepository->insertUsuarioRol($id, $user->rol);
                $this->userRepository->relacionCentroUsuario($id, $centroAnterior, $user->rol);
                $this->userRepository->relacionDietaUsuario($id, $dietaAnterior, $user->rol);
            }

            $this->userRepository->update($id, $user);
        }

        public function deleteUser(int $id): bool {
            return $this->userRepository->delete($id);
        }

        public function desactivarUser(int $id): bool {
            return $this->userRepository->desactivar($id);
        }

        public function getUserRol(int $id): string {
            return $this->userRepository->getRol($id);
        }
        
        
        public function getCentroId(int $id, string $rol): int {
            return $this->userRepository->capturarCentroId($id, $rol)
                ?? throw new \Exception("Centro no encontrado para el usuario con id $id y rol $rol");
        }

        private function validarFormatoPassword(string $password): void {
            $regex = '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';

            if (!preg_match($regex, $password)) {
                throw new \Exception("La contraseña debe contener al menos una mayúscula, un número y un carácter especial.");
            }
        }

         public function getUsersByCentro(int $centroId): array {
            return $this->userRepository->getUsersByCentro($centroId);
        }


    }