<x-layouts.main-layout pageTitle="Perfil de Usuário">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-6">
                <p class="display-6">DEFINIR NOVA SENHA</p>

                <form action="{{ route('change-password') }}" method="post">

                    @csrf

                    <div class="mb-3">
                        <label for="current_password" class="form-label">senha atual</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                        @error('current_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Defina a nova senha</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                        @error('new_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirmar a nova senha</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                        @error('new_password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mt-4">
                        <div class="col text-end">
                            <button type="submit" class="btn btn-secondary px-5">ALTERAR SENHA</button>
                        </div>
                    </div>

                </form>

                @if (session('server_errors'))    
                    <div class="alert alert-danger text-center mt-3">
                        {{ session('server_errors') }}
                    </div>
                @endif
                
                @if (session('success'))    
                    <div class="alert alert-primary text-center mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                <hr>

                <div class="card border-1 border-danger p-5 text-center">
                    Para remover permanentemente sua conta de Usuário no sistema digite o texto "ELIMINAR" no campo abaixo.
                

                    <form action="{{ route('delete_account')}}" method="post">
                        @csrf

                        <div class="my-3">
                            <input type="text" name="delete_confirmation" class="form-control text-center mt-3" placeholder="Digite a palavra aqui!">
                            @error('delete_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger">EXCLUIR CONTA</button>
                    </form> 
                </div>
        </div>
    </div>
</div>
</x-layouts.main-layout>