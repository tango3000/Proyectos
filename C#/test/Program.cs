using System;
using System.Collections.Generic;

/// Clase que convierte pulsaciones de teclado antiguo (T9) a texto
public class OldPhonePadConverter
{    
    /* 
    <summary>
    Converts a sequence of numbers to letters based on the cell phone's keypad.
    </summary>
    <param name="input">Input string with keystrokes (must end with #)</param>
    <returns>Decoded text or empty string if the input is invalid.</returns>
    
    */
    public string Keycvert(string input)
    {
        /*Validate user input
        Input must end with '#' and not be null or empty*/
        if (string.IsNullOrEmpty(input) || !input.EndsWith("#"))
        {
            return "";
        }

        /* Key mapping via a dictionary
            Each key has a string associated with the corresponding letters, even if I had included
            the missing 1 in the dictionary 
        */
        var keyphone = new Dictionary<char, string>()
        {
            {'2', "ABC"},  // Key 2: ABC
            {'3', "DEF"},  // Key 3: DEF
            {'4', "GHI"},  // Key 4: GHI
            {'5', "JKL"},  // Key 5: JKL
            {'6', "MNO"},  // Key 6: MNO
            {'7', "PQRS"}, // Key 7: PQRS
            {'8', "TUV"},  // Key 8: TUV
            {'9', "WXYZ"}  // Key 9: WXYZ
        };

        string Savendresult = "";       // Variable to store the result
        char? previousKey = null;      // Variable to store the previous key pressed
        int count = 0;            // Variable to count the number of times the same key is pressed

        /* Process each keystroke in the input, a for loop could also work
            But it would take up more memory */
        foreach (char currentKey in input)
        {
            if (currentKey == '#') // End of input
            {
                // Add the last character before '#'
                AddCurrentCharacter(ref Savendresult, previousKey, keyphone, count);
                break; // Exit the loop
            }

            switch (currentKey)
            {
                case '*': // Asterisk (delete)
                    AddCurrentCharacter(ref Savendresult, previousKey, keyphone, count);    
                    // If the last character is a space, remove it
                    if (Savendresult.Length > 0)
                    {
                        Savendresult = Savendresult.Substring(0, Savendresult.Length - 1);
                    }
                    // Reset state
                    previousKey = null;
                    count = 0;
                    break;

                case ' ': // Space (pause)
                    // Add the last character before space
                    AddCurrentCharacter(ref Savendresult, previousKey, keyphone, count);
                    // Add a space to the result
                    previousKey = null;
                    count = 0;
                    break;

                default: // Number key pressed
                    // If the key is a digit and exists in the keyphone dictionary
                    // Check if the current key is a digit and exists in the keyphone dictionary
                    if (char.IsDigit(currentKey) && keyphone.ContainsKey(currentKey))
                    {
                        if (currentKey == previousKey) // Same key pressed again
                        {
                            // Increment the count of the same key pressed
                            count++;
                        }
                        else{
                            // Add the last character before the new key
                            AddCurrentCharacter(ref Savendresult, previousKey, keyphone, count);
                            // Update the current key and reset the count
                            previousKey = currentKey;
                            count = 1; // Reset counter
                        }
                    }
                    break;
            }
        }

        return Savendresult;
    }


    /// Helper method to add the corresponding character to the result
    private void AddCurrentCharacter(ref string Savendresult, char? currentKey, 
            Dictionary<char, string> keyphone, int count)
    {
        if (currentKey.HasValue && keyphone.ContainsKey(currentKey.Value) && count > 0)
        {
            // Calculate letter index (0-based)
            int index = (count - 1) % keyphone[currentKey.Value].Length;
            // Add the corresponding letter to the result
            Savendresult += keyphone[currentKey.Value][index];
        }
    }
}

//Main class and method to run the program
public class Program
{
    public static void Main()
    {
        var converter = new OldPhonePadConverter();
        Console.WriteLine($"OldPhonePad(\"33#\") => {converter.Keycvert("33#")}");
        Console.WriteLine($"OldPhonePad(\"227*#\") => {converter.Keycvert("227*#")}");
        Console.WriteLine($"OldPhonePad(\"4433555 555666#\") => {converter.Keycvert("4433555 555666#")}");
        Console.WriteLine($"OldPhonePad(\"8 88777444666*664#\") => {converter.Keycvert("8 88777444666*664#")}");

        Console.WriteLine("\n\n");

    /*
    Test cases:

        Basic cases:

        1."2#" → "A"
        2."22#" → "B"
        3."222#" → "C"
        4."2222#" → "A" (wrap-around)
        5."23#" → "AD"
        6."2233#" → "BE"
        7."4433555 555666#" → "HELLO"

        Cases with asterisk (*):
        8. "22*#" → "" (complete erase)
        9. "222*#" → "A" (delete until 1 is left)
        10. "22*22#" → "B" (Delete and new key)
        11. "222333*#" → "AD" (Delete part of the sequence)
        12. "222*333#" → "D" (Delete and new key)

        Casos con espacios:
        13. "22 22#" → "AA" (Space as separator)
        14. "22 2#" → "A A" (Questionable interpretation)
        15. "4433555 555666#" → "HELLO" (Real example)

        Borderline cases:
        16. "#" → "" (mnime input)
        17. "*#" → "" (Delete and end)
        18. " #" → "" (space and end)
        19. "2222222222#" → "A" (repeated maximum)
        20. "2 2 2#" → "A A A"

        Casos inválidos:
        21. "" → "" (empty string)
        22. null → "" (null input)
        23. "22" → "" (no # at the end)
        24. "1#" → "" (unmapped key)
        25. "A23#" → "D" (invalid character at start)
        26. "23B#" → "AD" (invalid character in the middle)

        Complex cases:
        27. "8 88777444666*664#" → "TURING"
        28. "7777 7777666*6633#" → "PROGRAM"
        29. "2*2#" → "A" (erase and repeat)
        30. "22 2*#" → "A" (space and erase)

    */
    

    
        Console.WriteLine("********************************");
        Console.WriteLine("*           TEST CASES         *");
        Console.WriteLine("********************************");

        Console.WriteLine("\n\n");
    //Basic cases:
        Console.WriteLine("=================================");
        Console.WriteLine("1. BASIC CASES OF INDIVIDUAL KEYS");
        Console.WriteLine("=================================");
        Console.WriteLine($"OldPhonePad(\"2#\") => {converter.Keycvert("2#")}");
        Console.WriteLine($"OldPhonePad(\"22#\") => {converter.Keycvert("22#")}");
        Console.WriteLine($"OldPhonePad(\"222#\") => {converter.Keycvert("222#")}");
        Console.WriteLine($"OldPhonePad(\"2222#\") => {converter.Keycvert("2222#")}");
        Console.WriteLine($"OldPhonePad(\"23#\") => {converter.Keycvert("23#")}");
        Console.WriteLine($"OldPhonePad(\"2233#\") => {converter.Keycvert("2233#")}");

        Console.WriteLine("\n");

    //Cases with asterisk (*):
        Console.WriteLine("==========================");
        Console.WriteLine("2. Cases with asterisk (*)");
        Console.WriteLine("==========================");

        Console.WriteLine($"OldPhonePad(\"22*#\") => {converter.Keycvert("22*#")}");
        Console.WriteLine($"OldPhonePad(\"222*#\") => {converter.Keycvert("222*#")}");
        Console.WriteLine($"OldPhonePad(\"22*22#\") => {converter.Keycvert("22*22#")}");
        Console.WriteLine($"OldPhonePad(\"222333*#\") => {converter.Keycvert("222333*#")}");
        Console.WriteLine($"OldPhonePad(\"222*333#\") => {converter.Keycvert("222*333#")}");

        Console.WriteLine("\n");

    //Cases with spaces (" "):
        Console.WriteLine("====================");
        Console.WriteLine("3. CASES WITH SPACES");
        Console.WriteLine("====================");

        Console.WriteLine($"OldPhonePad(\"22 22#\") => {converter.Keycvert("22 22#")}");
        Console.WriteLine($"OldPhonePad(\"22 2#\") => {converter.Keycvert("22 2#")}");
        Console.WriteLine($"OldPhonePad(\"4433555 555666#\") => {converter.Keycvert("4433555 555666#")}");

        Console.WriteLine("\n");

    //Edge cases:
        Console.WriteLine("=============");
        Console.WriteLine("4. Edge cases");
        Console.WriteLine("=============");

        Console.WriteLine($"OldPhonePad(\"#\") => {converter.Keycvert("#")}");
        Console.WriteLine($"OldPhonePad(\"*#\") => {converter.Keycvert("*#")}");
        Console.WriteLine($"OldPhonePad(\" #\") => {converter.Keycvert(" #")}");
        Console.WriteLine($"OldPhonePad(\"2222222222#\") => {converter.Keycvert("2222222222#")}");
        Console.WriteLine($"OldPhonePad(\"2 2 2#\") => {converter.Keycvert("2 2 2#")}");

        Console.WriteLine("\n");

    //Invalid cases:
        Console.WriteLine("================");
        Console.WriteLine("5. Invalid cases");
        Console.WriteLine("================");

        Console.WriteLine($"OldPhonePad(\"\") => {converter.Keycvert("")}");
        Console.WriteLine($"OldPhonePad(null) => {converter.Keycvert(null)}");
        Console.WriteLine($"OldPhonePad(\"22\") => {converter.Keycvert("22")}");
        Console.WriteLine($"OldPhonePad(\"1#\") => {converter.Keycvert("1#")}");
        Console.WriteLine($"OldPhonePad(\"A23#\") => {converter.Keycvert("A23#")}");
        Console.WriteLine($"OldPhonePad(\"23B#\") => {converter.Keycvert("23B#")}");

        Console.WriteLine("\n");

    //Complex cases:
        Console.WriteLine("===============");
        Console.WriteLine("6.Complex cases");
        Console.WriteLine("===============");

        Console.WriteLine($"OldPhonePad(\"8 88777444666*664#\") => {converter.Keycvert("8 88777444666*664#")}");
        Console.WriteLine($"OldPhonePad(\"7777 7777666*6633#\") => {converter.Keycvert("7777 7777666*6633#")}");
        Console.WriteLine($"OldPhonePad(\"2*2#\") => {converter.Keycvert("2*2#")}");
        Console.WriteLine($"OldPhonePad(\"22 2*#\") => {converter.Keycvert("22 2*#")}");
        Console.WriteLine($"OldPhonePad(\"227*#\") => {converter.Keycvert("227*#")}");

    }
}