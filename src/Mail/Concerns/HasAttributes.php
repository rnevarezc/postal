<?php

declare(strict_types=1);

namespace Postal\Mail\Concerns;

trait HasAttributes
{
    /**
     * Set the attributes
     * 
     * Uses a defined setter in the class to set each
     * attribute.
     *
     * @param array $attributes
     * @return void
     */
    public function setAttributes(array $attributes)
    {
        foreach($attributes as $field => $value){
            $setter = $this->getSetter($field);
            if ( !is_null($setter) ){
                $this->{$setter}($value);
            }
        }
    }

    /**
     * Get the message attributes
     *
     * @return array
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * Get a setter method for a $field if defined in the attributes.
     *
     * @param string $field
     * @return string|null
     */
    protected function getSetter(string $field) :? string
    {
        $setter = 'set' . str_replace('_', '', ucwords($field, '_'));
        
        return method_exists($this, $setter) ? $setter : null;
    }
    
    /**
     * Add Recipients(s) to the proper $field
     *
     * @param array|string $value
     * @param string $field
     * @return void
     */
    protected function addRecipients($value, string $field)
    {
        $value = !is_array($value) ? (array) $value : $value;

        $this->attributes[$field] = array_unique(
            array_merge($this->attributes[$field], $value)
        );

        return $this;
    }
}