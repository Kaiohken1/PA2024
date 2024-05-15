
<table class="w-full bg-white shadow-md rounded my-4">
@foreach ( $appartementAvis as $avis)
             <tbody class="text-gray-600 text-sm font-light">
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-6 text-left">{{ $avis->reservation->user->first_name }}</td>
                                <td class="py-3 px-6 text-left">{{ $avis->comment }}</td>
                                @if($avis->reservation->user_id == auth()->user()->id)
                                <td class="py-3 px-6 text-left">
                                        <form action="{{ route('avis.destroy', ['appartement' => $avis->appartement_id, 'avi' => $avis->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                </td>
                                <td class="py-3 px-6 text-left">
                                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                                <a href="{{ route('avis.edit', ['appartement' => $avis->appartement_id, 'avis' => $avis->id]) }}">Modifier</a>
                                            </button>
                                </td>
                                <td class="py-3 px-6 text-left">
                                @endif
                            </tr>
                        </tbody>
                    @endforeach
                </table>