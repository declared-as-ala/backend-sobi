<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Product model. Stock: qte = source of truth (quantity in stock);
 * rupture = out-of-stock flag (1 = out of stock, 0 = in stock).
 * API returns real values from DB; no transformation.
 */
class Product extends Model
{
    protected $casts = [];
    /**
     * Override toArray to use raw rupture value for API/serialization
     * This ensures JSON responses contain the true DB values (0=in stock, 1=out of stock)
     * while Voyager forms still see the accessor-inverted values
     */
    public function toArray()
    {
        $array = parent::toArray();
        // Get the raw DB value for rupture instead of the accessor value
        if (isset($this->attributes['rupture'])) {
            $array['rupture'] = (int) $this->attributes['rupture'];
        }
        return $array;
    }

    /**
     * Accessor: Invert rupture for display (DB: 0=in stock, 1=out of stock)
     * Voyager checkbox: true/checked shows "En Stock", false/unchecked shows "Rupture de sock"
     * So we need to invert: 0->1, 1->0
     */
    public function getRuptureAttribute($value)
    {
        return $value ? 0 : 1; // 0 (in stock) -> 1 (checked/En Stock), 1 (out of stock) -> 0 (unchecked/Rupture)
    }

    /**
     * Mutator: Invert rupture when saving from Voyager form.
     * Reverse the accessor logic: 1->0, 0->1
     */
    public function setRuptureAttribute($value)
    {
        $this->attributes['rupture'] = $value ? 0 : 1;
    }
    public function sous_categorie(){
        return $this->belongsTo(SousCategory::class , 'sous_categorie_id' , 'id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class , 'product_tags');
    }

    public function aromes(){
        return $this->belongsToMany(Aroma::class , 'product_aromas');
    }
    public function reviews(){
        return $this->hasMany(Review::class )->where('publier' , 1);
    }
}
