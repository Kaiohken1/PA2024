<?
use App\Models\User;
use App\Notifications\ReservationEditRequest;

public function confirmEdit(Request $request, $id)
{
    // Code pour confirmer la modification et envoyer la notification à l'administrateur
    $admin = User::where('role', 'admin')->first();
    $admin->notify(new ReservationEditRequest($id));

    return return redirect()->route('/');
    ->with('sucess',"Votre demande a bien etait envoyer a l'admin en attente de confirmation");
}
