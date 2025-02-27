---
title: 'Reply Keyboards' 
menuTitle: 'Reply Keyboards' 
description: ''
category: 'Features' 
fullscreen: false 
position: 33
---

When sending a message, Telegram can be instructed to replace the standard phone keyboard with a custom one (see [here](https://core.telegram.org/bots#keyboards) for detailed info):

<img src="screenshots/reply-keyboard.jpeg" />

## Attaching a keyboard

A keyboard can be added to a message using the `->replyKeyboard()` command, passing a new `ReplyKeyboard` object as argument.

`ReplyKeyboard` has a fluent way to define its buttons and other properties (rows, button chunking, etc.):

buttons can be set up using the `ReplyKeyboard::make()->buttons()` method and are defined as a `ReplyButton` array

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

Telegraph::message('hello world')
    ->replyKeyboard(ReplyKeyboard::make()->buttons([
       ReplyButton::make('foo')->requestPoll(),
       ReplyButton::make('bar')->requestQuiz(),
       ReplyButton::make('baz')->webApp('https://webapp.dev'),
    ]))->send();
```

Additionally, a keyboard can be added to a message using a closure:

```php
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

Telegraph::message('hello world')
->replyKeyboard(function(Keyboard $keyboard){
    return $keyboard
        ->button('foo')->requestPoll()
        ->button('bar')->requestQuiz()
        ->button('baz')->webApp('https://webapp.dev');
})->send();
```

## Keyboard Rows

A keyboard will normally place one button per row, this behaviour can be customized by defining rows, by setting individual buttons width or by chunking buttons

### by rows

```php
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

$keyboard = ReplyKeyboard::make()
    ->row([
        ReplyButton::make('Send Contact')->requestContact(),
        ReplyButton::make('Send Location')->requestLocation(),
    ])
    ->row([
        ReplyButton::make('Quiz')->requestQuiz(),
    ]);
```

### by setting buttons width

A button relative width can be set using a float number the total width percentage to be taken. Buttons will flow through the rows according to their width

this example would define two buttons on the first row and a large button on the second one:

```php
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

$keyboard = ReplyKeyboard::make()
    ->button('Text')
    ->button('Send Contact')->requestContact()
    ->button('Send Location')->requestLocation()
    ->button('Create Quiz')->requestQuiz()
    ->button('Create Poll')->requestPoll()
    ->button('Start WebApp')->webApp('https://web.app.dev');
```

**notes**

 - A button default width is 1 (that's to say, the entire row width)
 - Each width is defined as a float between 0 and 1 that represents the floating point percentage of the row taken by the button.
 - each button will fill the current row or flow in the subsequent one if there isn't enough space left

### by chunking

Buttons can be authomatically chunked in rows using the `->chunk()` method.

This example would return a first row of two buttons, a second row of two buttons and the last row with the remaining button.

```php
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

$keyboard = ReplyKeyboard::make()
   ->button('Text')
    ->button('Send Contact')->requestContact()
    ->button('Send Location')->requestLocation()
    ->button('Create Quiz')->requestQuiz()
    ->button('Create Poll')->requestPoll()
    ->button('Start WebApp')->webApp('https://web.app.dev')
    ->chunk(2);
```

## Resize a keyboard

Clients can be requested to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.

```php
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

$keyboard = ReplyKeyboard::make()
    ->button('Send Contact')->requestContact()
    ->button('Send Location')->requestLocation()
    ->resize();
```

## One time keyboards

Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be available, but clients will automatically display the usual letter-keyboard in the chat – the user can press a special button in the input field to see the custom keyboard again

```php
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

$keyboard = ReplyKeyboard::make()
    ->button('Send Contact')->requestContact()
    ->button('Send Location')->requestLocation()
    ->oneTime();
```

## Adding a placeholder for the input field

The placeholder to be shown in the input field when the keyboard is active can be set with:

```php
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

$keyboard = ReplyKeyboard::make()
    ->button('Send Contact')->requestContact()
    ->button('Send Location')->requestLocation()
    ->inputPlaceholder("Waiting for input...");
```

## Applying a keyboard to a specific user

A keyboard can be applied to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.

Example: A user requests to change the bot's language, bot replies to the request with a keyboard to select the new language. Other users in the group don't see the keyboard.

```php
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

$keyboard = ReplyKeyboard::make()
    ->button('Send Contact')->requestContact()
    ->button('Send Location')->requestLocation()
    ->selective();
```

## Removing a keyboard

A reply keyboard can be removed from clients with this call:

```php
Telegraph::message('command received')
    ->removeReplyKeyboard()
    ->send();
```

### Removing a keyboard for a specific user

To remove the keyboard for a specific user, simply pass `true` parameter to the `removeReplyKeyboard` method.

Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.

Example: A user votes in a poll, bot returns confirmation message in reply to the vote and removes the keyboard for that user, while still showing the keyboard with poll options to users who haven't voted yet.

```php
Telegraph::message('command received')
    ->removeReplyKeyboard(true)
    ->send();
```

## Conditional methods

a `when` method allows to execute a closure when the given condition is verified

```php
ReplyKeyboard::make()
    ->button('Send Contact')->requestContact()
     ->when($shouldRequestLocation, fn(ReplyKeyboard $keyboard) => $keyboard->button('Send Location')->requestLocation())
```
