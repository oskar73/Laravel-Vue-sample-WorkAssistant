export default {
  data: {
    items: [
      {
        title: 'Add a title',
        description:
          'You can use this element to explain to visitors what you do or inform them about other subjects. For instance, what is your passion and why or what does your company offer, i.e. products and services. You can hide this element in the menu on the right',
        buttons: [
          {
            title: 'Read more'
          }
        ]
      },
      {
        title: 'Add a title',
        description:
          'You can use this element to explain to visitors what you do or inform them about other subjects. For instance, what is your passion and why or what does your company offer, i.e. products and services. You can hide this element in the menu on the right',
        buttons: [
          {
            title: 'Read more'
          }
        ]
      }
    ],
    elements: {
      title: 'Welcome to our website',
      subtitle: 'Learn more about what we do',
      description:
        "You can edit text on your website by double clicking on a text box on your website. Alternatively, when you select a text box a settings menu will appear. Selecting 'Edit Text' from this menu will also allow you to edit the text within this text box. Remember to keep your wording friendly, approachable and easy to understand as if you were talking to your customer. You can edit text on your website by double clicking on a text box on your website. Alternatively, when you select a text box a settings menu will appear. Selecting 'Edit Text' from this menu will also allow you to edit the text within this text box. Remember to keep your wording friendly, approachable and easy to understand as if you were talking to your customer"
    }
  },
  setting: {
    elements: {
      image: true,
      title: true,
      subtitle: true,
      line: true,
      description: true,
      buttons: true
    },
    layouts: {
      shadow: true,
      sectionSize: true,
      contentAlignment: 'left',
      shape: 'square'
    },
    items: {
      image: false,
      title: true,
      description: true,
      buttons: true
    }
  },
  background: {
    color: true,
    image: true,
    video: true,
    pattern: true
  }
}
