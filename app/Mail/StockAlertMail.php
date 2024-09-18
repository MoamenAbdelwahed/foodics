<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ingredient;

class StockAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ingredient;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Stock Alert: ' . $this->ingredient->name)
            ->view('emails.stock_alert')
            ->with([
                'ingredientName' => $this->ingredient->name,
                'remainingStock' => $this->ingredient->stock,
            ]);
    }
}
