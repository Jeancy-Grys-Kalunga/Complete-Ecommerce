<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\PostComment;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Rules\MatchOldPassword;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function index()
    {
        return view('user.index');
    }

    public function profile()
    {
        $profile = Auth()->user();
        // return $profile;
        return view('user.users.profile')->with('profile', $profile);
    }

    public function profileUpdate(Request $request, $id)
    {
        // return $request->all();
        $user = User::findOrFail($id);
        $data = $request->all();
        $status = $user->fill($data)->save();
        if($status) {
            request()->session()->flash('success', 'Profil modifié avec succès');
        } else {
            request()->session()->flash('error', 'Quelque chose ce mal passé veuillez réessayer plus tard !');
        }
        return redirect()->back();
    }

    // Order
    public function orderIndex()
    {
        $orders = Order::orderBy('id', 'DESC')->where('user_id', auth()->user()->id)->paginate(10);
        return view('user.order.index')->with('orders', $orders);
    }
    public function userOrderDelete($id)
    {
        $order = Order::find($id);
        if($order) {
            if($order->status == "process" || $order->status == 'delivered' || $order->status == 'cancel') {
                return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer cette commande maintenant');
            } else {
                $status = $order->delete();
                if($status) {
                    request()->session()->flash('success', 'Commande supprimée avec succès ');
                } else {
                    request()->session()->flash('error', 'Vous ne pouvez pas supprimer cette commande');
                }
                return redirect()->route('user.order.index');
            }
        } else {
            request()->session()->flash('error', 'Commande non trouvée');
            return redirect()->back();
        }
    }

    public function orderShow($id)
    {
        $order = Order::find($id);
        // return $order;
        return view('user.order.show')->with('order', $order);
    }
    // Product Review
    public function productReviewIndex()
    {
        $reviews = ProductReview::getAllUserReview();
        return view('user.review.index')->with('reviews', $reviews);
    }

    public function productReviewEdit($id)
    {
        $review = ProductReview::find($id);
        // return $review;
        return view('user.review.edit')->with('review', $review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productReviewUpdate(Request $request, $id)
    {
        $review = ProductReview::find($id);
        if($review) {
            $data = $request->all();
            $status = $review->fill($data)->update();
            if($status) {
                request()->session()->flash('success', 'Commentaire modifié avec succès');
            } else {
                request()->session()->flash('error', 'Quelque chose ce mal passé veuillez réessayer plus tard !!');
            }
        } else {
            request()->session()->flash('error', 'Commentaire non trouvé !!');
        }

        return redirect()->route('user.productreview.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productReviewDelete($id)
    {
        $review = ProductReview::find($id);
        $status = $review->delete();
        if($status) {
            request()->session()->flash('success', 'Commentaire supprimé avec succès ');
        } else {
            request()->session()->flash('error', 'Quelque chose ce mal passé veuillez réessayer plus tard !!');
        }
        return redirect()->route('user.productreview.index');
    }

    public function userComment()
    {
        $comments = PostComment::getAllUserComments();
        return view('user.comment.index')->with('comments', $comments);
    }
    public function userCommentDelete($id)
    {
        $comment = PostComment::find($id);
        if($comment) {
            $status = $comment->delete();
            if($status) {
                request()->session()->flash('success', 'Commentaire supprimé avec succès');
            } else {
                request()->session()->flash('error', 'Quelque chose ce mal passé veuillez réessayer plus tard ');
            }
            return back();
        } else {
            request()->session()->flash('error', 'Aucun commantaire trouvé pour ce Post ');
            return redirect()->back();
        }
    } public function userCommentEdit($id)
    {
        $comments = PostComment::find($id);
        if($comments) {
            return view('user.comment.edit')->with('comment', $comments);
        } else {
            request()->session()->flash('error', 'Commentaire supprimé avec succès');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userCommentUpdate(Request $request, $id)
    {
        $comment = PostComment::find($id);
        if($comment) {
            $data = $request->all();
            // return $data;
            $status = $comment->fill($data)->update();
            if($status) {
                request()->session()->flash('success', 'Commentaire modifié avec succès');
            } else {
                request()->session()->flash('error', 'Quelque chose ce mal passé veuillez réessayer plus tard !!');
            }
            return redirect()->route('user.post-comment.index');
        } else {
            request()->session()->flash('error', 'Commentaire non trouvé');
            return redirect()->back();
        }

    }

    public function changePassword()
    {
        return view('user.layouts.userPasswordChange');
    }
    public function changPasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword()],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('user')->with('success', 'Mot de passe modifié avec succès');
    }


}
