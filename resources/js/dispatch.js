export default function  (element, name, payload = {})  {
    const event = new CustomEvent(name, payload)
    element.dispatchEvent(event)
}
