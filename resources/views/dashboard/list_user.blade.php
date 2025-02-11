@extends('layouts.dashboard_layout')
@section('dashboard')
    <div class="col-12 ">
        <div class="bg-light rounded h-100 p-4">
            <div class="d-flex justify-content-between mt-5">
                <div>
                    <h6 class="mb-4 fs-2">Liste des utilisateurs</h6>
                </div>
                <div>
                    <button type="button" class="btn btn-primary">Ajouter</button>
                    <button type="button" class="btn btn-secondary">Trier par</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom complet</th>
                            <th scope="col">Email</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">Adresse</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">1</th>
                                <td>{{ $user->name }} {{ $user->lastname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->address }}</td>
                                <td>
                                    @if ($user->role == 'patient')
                                        Patient
                                    @elseif ($user->role == 'doctor')
                                        Docteur
                                    @elseif ($user->role == 'secretary')
                                        Secrétaire
                                    @else
                                        {{ $user->role }}
                                    @endif
                                </td>
                                <td>
                                    @if ($user->role == 'doctor' || $user->role == 'secretary')
                                        Nul
                                    @elseif ($user->status == 'pending')
                                        En attente
                                    @elseif ($user->status == 'consulted')
                                        Consulté
                                    @elseif ($user->status == 'canceled')
                                        Annulé
                                    @elseif ($user->status == 'completed')
                                        Complété
                                    @else
                                        {{ $user->status }}
                                    @endif
                                </td>
                                <td class="">
                                    <a href="#" class="btn text-primary"><i class="bi bi-pen-fill"></i></a>
                                    <button type="button" class="btn text-danger" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop"><i class="bi bi-trash3-fill"></i></button>
                                </td>
                            </tr>
                            </tr>
                        @endforeach
                    </tbody>
                </table>



                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Understood</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
